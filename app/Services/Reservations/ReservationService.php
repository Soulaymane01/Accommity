<?php

namespace App\Services\Reservations;

use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Models\Reservations\Reservation;
use App\Enums\ModeReservation;
use App\Enums\TypeActeurAnnulation;
use App\Models\Annonces\Annonce;
use App\Events\ReservationConfirmee;
use App\Events\ReservationAnnulee;
use App\Models\Utilisateurs\User;
use App\Facades\AppNotification;
use App\Enums\TypeAlerte;
use App\Services\Paiements\PaiementService;
use App\Services\Paiements\RemboursementService;

class ReservationService
{
    protected $repository;
    protected $statutService;
    protected $minuterieService;
    protected $paiementService;
    protected $remboursementService;
    protected $calendrierService;

    public function __construct(
        ReservationRepositoryInterface $repository,
        StatutService $statutService,
        MinuterieService $minuterieService,
        PaiementService $paiementService,
        RemboursementService $remboursementService,
        \App\Services\Annonces\CalendrierService $calendrierService
    ) {
        $this->repository = $repository;
        $this->statutService = $statutService;
        $this->minuterieService = $minuterieService;
        $this->paiementService = $paiementService;
        $this->remboursementService = $remboursementService;
        $this->calendrierService = $calendrierService;
    }

    public function soumettreDemande(string $idAnnonce, array $dates, int $nbVoy, ?string $message): Reservation
    {
        $annonce = Annonce::findOrFail($idAnnonce);
        
        $start = \Carbon\Carbon::parse($dates['dateArrivee']);
        $end = \Carbon\Carbon::parse($dates['dateDepart']);
        
        if ($end->lte($start)) {
            throw new Exception("La date de départ doit être après la date d'arrivée.");
        }

        // Verifier disponibilité (Seulement les confirmations bloquent la soumission)
        $overlap = Reservation::where('id_annonce', $idAnnonce)
            ->whereIn('statut', [\App\Enums\StatutReservation::CONFIRMEE, \App\Enums\StatutReservation::EN_COURS])
            ->where(function($query) use ($dates) {
                $query->where(function($q) use ($dates) {
                    $q->where('date_arrivee', '>=', $dates['dateArrivee'])
                      ->where('date_arrivee', '<', $dates['dateDepart']);
                })->orWhere(function($q) use ($dates) {
                    $q->where('date_depart', '>', $dates['dateArrivee'])
                      ->where('date_depart', '<=', $dates['dateDepart']);
                })->orWhere(function($q) use ($dates) {
                    $q->where('date_arrivee', '<=', $dates['dateArrivee'])
                      ->where('date_depart', '>=', $dates['dateDepart']);
                });
            })->exists();

        if ($overlap) {
            throw new Exception("Désolé, ce logement est déjà réservé pour ces dates.");
        }
        
        $mode = $annonce->mode_reservation ?? ModeReservation::DEMANDE;
        $nights = $start->diffInDays($end);
        
        $data = [
            'id_annonce' => $idAnnonce,
            'id_voyageur' => auth()->id(),
            'id_hote' => $annonce->id_hote,
            'date_arrivee' => $dates['dateArrivee'],
            'date_depart' => $dates['dateDepart'],
            'nb_voyageurs' => $nbVoy,
            'statut' => \App\Enums\StatutReservation::EN_ATTENTE,
            'mode_reservation' => $mode,
            'montant_total' => $annonce->tarif_nuit * $nights,
            'frais_service' => ($annonce->tarif_nuit * $nights) * 0.1, // 10% fees
            'message_optionnel' => $message,
            'id_politique' => $annonce->id_politique
        ];

        $reservation = $this->repository->create($data);

        // Payment is now handled on the dedicated payment page.
        // For DEMANDE mode: start the 24h timer so the host can accept/refuse.
        if ($mode === ModeReservation::DEMANDE) {
            $this->minuterieService->demarrerMinuterie24h($reservation->id_reservation);
        }

        $hote = User::find($annonce->id_hote);
        if ($hote) {
            $msg = $mode === ModeReservation::INSTANTANEE 
                ? "Nouvelle réservation instantanée ! Un voyageur vient de réserver votre logement." 
                : "Nouvelle demande de réservation pour votre logement. Veuillez l'accepter ou la refuser dans les 24h.";
            AppNotification::creerNotification($hote, 'Nouvelle Réservation 🏡', $msg, TypeAlerte::Reservation);
        }

        return $reservation;
    }

    public function accepterDemande(string $idReservation): void
    {
        $reservation = $this->repository->findById($idReservation);
        if (!$reservation) throw new Exception("Not found");

        // Vérifier une dernière fois l'absence de conflit avant confirmation
        $overlap = Reservation::where('id_annonce', $reservation->id_annonce)
            ->where('id_reservation', '!=', $reservation->id_reservation)
            ->whereIn('statut', [\App\Enums\StatutReservation::CONFIRMEE, \App\Enums\StatutReservation::EN_COURS])
            ->where(function($query) use ($reservation) {
                $query->where(function($q) use ($reservation) {
                    $q->where('date_arrivee', '>=', $reservation->date_arrivee)
                      ->where('date_arrivee', '<', $reservation->date_depart);
                })->orWhere(function($q) use ($reservation) {
                    $q->where('date_depart', '>', $reservation->date_arrivee)
                      ->where('date_depart', '<=', $reservation->date_depart);
                })->orWhere(function($q) use ($reservation) {
                    $q->where('date_arrivee', '<=', $reservation->date_arrivee)
                      ->where('date_depart', '>=', $reservation->date_depart);
                });
            })->exists();

        if ($overlap) {
            throw new Exception("Impossible d'accepter : un autre voyageur a déjà réservé ces dates.");
        }

        $this->statutService->appliquerTransition($reservation, \App\Enums\StatutReservation::CONFIRMEE);
        $this->minuterieService->annulerMinuterie($idReservation);

        $voyageur = User::find($reservation->id_voyageur);
        if ($voyageur) {
            AppNotification::creerNotification($voyageur, 'Demande Acceptée ! 🎉', "L'hôte a accepté votre demande de réservation.", TypeAlerte::Reservation);
        }

        // Notify user about payment and trigger dispatching event
        event(new ReservationConfirmee($reservation));
    }

    public function confirmerEtPayer(string $idReservation, string $moyenPaiement = 'carte_bancaire'): void
    {
        $reservation = $this->repository->findById($idReservation);
        
        // PK_PAIEMENTS Integration: Execute payment
        $this->paiementService->effectuerPaiement($reservation, $reservation->montant_total, $moyenPaiement);
        
        $this->statutService->appliquerTransition($reservation, \App\Enums\StatutReservation::CONFIRMEE);
        event(new ReservationConfirmee($reservation));
    }

    /**
     * For DEMANDE reservations: record the payment but keep the reservation EN_ATTENTE.
     * The host still needs to accept it before it becomes confirmed.
     */
    public function enregistrerPaiementDemande(string $idReservation, string $moyenPaiement = 'carte_bancaire'): void
    {
        $reservation = $this->repository->findById($idReservation);

        // Record the payment (money is held)
        $this->paiementService->effectuerPaiement($reservation, $reservation->montant_total, $moyenPaiement);

        // Keep status as EN_ATTENTE — the host must now accept or refuse
        // Notify host that a funded request has arrived
        $hote = \App\Models\Utilisateurs\User::find($reservation->id_hote);
        if ($hote) {
            AppNotification::creerNotification(
                $hote,
                'Nouvelle demande avec paiement 💳',
                'Un voyageur a payé et attend votre approbation pour réserver votre logement. Acceptez ou refusez dans les 24h.',
                TypeAlerte::Reservation
            );
        }

        // Notify the voyageur that their request is pending
        $voyageur = \App\Models\Utilisateurs\User::find($reservation->id_voyageur);
        if ($voyageur) {
            AppNotification::creerNotification(
                $voyageur,
                'Demande envoyée 📬',
                'Votre paiement a été enregistré. Votre demande est en attente d\'approbation de l\'hôte.',
                TypeAlerte::Reservation
            );
        }
    }

    public function refuserDemande(string $idReservation, ?string $motif = null): void
    {
        $reservation = $this->repository->findById($idReservation);
        $reservation->update(['motif_refus' => $motif]);
        $this->statutService->appliquerTransition($reservation, \App\Enums\StatutReservation::REFUSEE);
        $this->minuterieService->annulerMinuterie($idReservation);
        
        // Ensure calendar dates are freed
        $this->calendrierService->libererDatesReservation($reservation->id_annonce, $reservation->date_arrivee, $reservation->date_depart);

        // If the demande was funded, trigger refund
        if ($reservation->paiement) {
            $this->remboursementService->traiterAnnulation($reservation, TypeActeurAnnulation::HOTE, $reservation->montant_total, 'Demande refusée');
        }

        $voyageur = User::find($reservation->id_voyageur);
        if ($voyageur) {
            AppNotification::creerNotification($voyageur, 'Demande Refusée', "L'hôte a malheureusement dû refuser votre demande de réservation.", TypeAlerte::Systeme);
        }
    }

    public function annulerReservation(string $idReservation, TypeActeurAnnulation $acteur): void
    {
        $reservation = $this->repository->findById($idReservation);
        
        // 1. Verifier si l'annulation est encore possible
        if (in_array($reservation->statut, [\App\Enums\StatutReservation::TERMINEE, \App\Enums\StatutReservation::REFUSEE, \App\Enums\StatutReservation::ANNULEE])) {
            throw new Exception("Cette réservation ne peut plus être annulée.");
        }

        // 2. Traitement des remboursements (PK_PAIEMENTS Integration)
        $apercu = $this->calculerApercuAnnulation($idReservation);
        $this->remboursementService->traiterAnnulation($reservation, $acteur, $apercu['montant_remboursement'], $apercu['message']);
        
        // 3. Mise à jour du statut
        $this->statutService->appliquerTransition($reservation, \App\Enums\StatutReservation::ANNULEE);
        $reservation->update(['acteur_annulation' => $acteur]);
        $this->minuterieService->annulerMinuterie($idReservation);
        
        // 4. Débloquer le calendrier (PK_ANNONCES Integration)
        $this->calendrierService->libererDatesReservation($reservation->id_annonce, $reservation->date_arrivee, $reservation->date_depart);
        
        $voyageur = User::find($reservation->id_voyageur);
        $hote = User::find($reservation->id_hote);
        
        if ($acteur === TypeActeurAnnulation::VOYAGEUR && $hote) {
            AppNotification::creerNotification($hote, 'Réservation Annulée', "Le voyageur a annulé sa réservation.", TypeAlerte::Systeme);
        } elseif ($acteur === TypeActeurAnnulation::HOTE && $voyageur) {
            AppNotification::creerNotification($voyageur, 'Réservation Annulée', "L'hôte a annulé votre réservation.", TypeAlerte::Systeme);
        }
        
        event(new ReservationAnnulee($reservation));
    }

    public function calculerApercuAnnulation(string $idReservation): array
    {
        $reservation = $this->repository->findById($idReservation);
        $politique = $reservation->politique;

        if (!$politique || $reservation->statut === \App\Enums\StatutReservation::EN_ATTENTE) {
            return [
                'montant_total' => $reservation->montant_total,
                'montant_remboursement' => $reservation->montant_total,
                'pourcentage' => 100,
                'message' => "Annulation gratuite pour les demandes en attente."
            ];
        }

        $now = now();
        $arrivee = \Carbon\Carbon::parse($reservation->date_arrivee);
        $joursAvant = $now->diffInDays($arrivee, false);

        $taux = 0;
        $message = "Aucun remboursement possible selon la politique de l'hôte.";

        if ($joursAvant >= $politique->delai_remb_total) {
            $taux = 100;
            $message = "Remboursement intégral autorisé.";
        } elseif ($joursAvant >= $politique->delai_remb_partiel) {
            $taux = (float)$politique->taux_remb_partiel;
            $message = "Remboursement partiel de {$taux}% autorisé.";
        }

        $rembourse = ($reservation->montant_total * $taux) / 100;

        return [
            'montant_total' => (float)$reservation->montant_total,
            'montant_remboursement' => (float)$rembourse,
            'pourcentage' => $taux,
            'message' => $message
        ];
    }

    public function setStatutEnCours(string $idReservation): void
    {
        $reservation = $this->repository->findById($idReservation);
        $this->statutService->appliquerTransition($reservation, \App\Enums\StatutReservation::EN_COURS);
    }
    
    public function marquerExpirée(string $idReservation): void
    {
        $reservation = $this->repository->findById($idReservation);
        if ($reservation && $reservation->statut === \App\Enums\StatutReservation::EN_ATTENTE) {
            $this->statutService->appliquerTransition($reservation, \App\Enums\StatutReservation::EXPIREE);
            $this->minuterieService->annulerMinuterie($idReservation);
            
            $voyageur = User::find($reservation->id_voyageur);
            if ($voyageur) {
                AppNotification::creerNotification($voyageur, 'Demande Expirée', "L'hôte n'a pas répondu à temps. Votre demande de réservation a expiré.", TypeAlerte::Systeme);
            }
        }
    }

    public function consulterDemandesEnAttente(string $idHote)
    {
        return $this->repository->getEnAttenteByHote($idHote);
    }
}
