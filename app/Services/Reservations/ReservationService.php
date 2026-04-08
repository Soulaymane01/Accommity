<?php

namespace App\Services\Reservations;

use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Models\Reservations\Reservation;
use App\Enums\ModeReservation;
use App\Enums\TypeActeurAnnulation;
use App\Models\Annonces\Annonce;
use App\Events\ReservationConfirmee;
use App\Events\ReservationAnnulee;
use Exception;

class ReservationService
{
    protected $repository;
    protected $statutService;
    protected $minuterieService;

    public function __construct(
        ReservationRepositoryInterface $repository,
        StatutService $statutService,
        MinuterieService $minuterieService
    ) {
        $this->repository = $repository;
        $this->statutService = $statutService;
        $this->minuterieService = $minuterieService;
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

        if ($mode === ModeReservation::INSTANTANEE) {
            $this->confirmerEtPayer($reservation->id_reservation, 'default_payment');
        } else {
            $this->minuterieService->demarrerMinuterie24h($reservation->id_reservation);
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

        // Notify user about payment and trigger dispatching event
        event(new ReservationConfirmee($reservation));
    }

    public function confirmerEtPayer(string $idReservation, string $moyenPaiement): void
    {
        $reservation = $this->repository->findById($idReservation);
        
        // PK_PAIEMENTS STUB: Execute payment integration logic here
        
        $this->statutService->appliquerTransition($reservation, \App\Enums\StatutReservation::CONFIRMEE);
        event(new ReservationConfirmee($reservation));
    }

    public function refuserDemande(string $idReservation, ?string $motif = null): void
    {
        $reservation = $this->repository->findById($idReservation);
        $reservation->update(['motif_refus' => $motif]);
        $this->statutService->appliquerTransition($reservation, \App\Enums\StatutReservation::REFUSEE);
        $this->minuterieService->annulerMinuterie($idReservation);
    }

    public function annulerReservation(string $idReservation, TypeActeurAnnulation $acteur): void
    {
        $reservation = $this->repository->findById($idReservation);
        
        // 1. Verifier si l'annulation est encore possible
        if (in_array($reservation->statut, [\App\Enums\StatutReservation::TERMINEE, \App\Enums\StatutReservation::REFUSEE, \App\Enums\StatutReservation::ANNULEE])) {
            throw new Exception("Cette réservation ne peut plus être annulée.");
        }

        // 2. Traitement des remboursements (PK_PAIEMENTS STUB)
        $apercu = $this->calculerApercuAnnulation($idReservation);
        
        // 3. Mise à jour du statut
        $this->statutService->appliquerTransition($reservation, \App\Enums\StatutReservation::ANNULEE);
        $reservation->update(['acteur_annulation' => $acteur]);
        $this->minuterieService->annulerMinuterie($idReservation);
        
        // 4. Débloquer le calendrier (PK_ANNONCES Integration)
        // La recherche exclut déjà les 'Annulée', donc c'est automatique.
        
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
        }
    }

    public function consulterDemandesEnAttente(string $idHote)
    {
        return $this->repository->getEnAttenteByHote($idHote);
    }
}
