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
        // Mock annonce for now to understand correct properties. We get it from DB.
        $annonce = Annonce::findOrFail($idAnnonce);
        
        // Verifier disponibilité
        // ... (This would be another service check `CalendrierService::verifierDisponibilite($idAnnonce, $dates)`)
        
        $mode = ModeReservation::tryFrom($annonce->mode_reservation) ?? ModeReservation::DEMANDE;
        
        $data = [
            'id_annonce' => $idAnnonce,
            'id_voyageur' => auth()->id() ?? 'mocked-voyageur-id', // auth id
            'id_hote' => $annonce->id_hote,
            'date_arrivee' => $dates['dateArrivee'],
            'date_depart' => $dates['dateDepart'],
            'nb_voyageurs' => $nbVoy,
            'statut' => \App\Enums\StatutReservation::EN_ATTENTE,
            'mode_reservation' => $mode,
            'montant_total' => $annonce->tarif_nuit * 1, // pseudo calc
            'frais_service' => 0,
            'message_optionnel' => $message,
            'id_politique' => $annonce->id_politique
        ];

        $reservation = $this->repository->create($data);

        if ($mode === ModeReservation::INSTANTANEE) {
            // Confirm immediately
            $this->confirmerEtPayer($reservation->id_reservation, 'default_payment');
        } else {
            // Start 24h timer
            $this->minuterieService->demarrerMinuterie24h($reservation->id_reservation);
        }

        return $reservation;
    }

    public function accepterDemande(string $idReservation): void
    {
        $reservation = $this->repository->findById($idReservation);
        if (!$reservation) throw new Exception("Not found");

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
        $this->statutService->appliquerTransition($reservation, \App\Enums\StatutReservation::REFUSEE);
        $this->minuterieService->annulerMinuterie($idReservation);
        // Dispatch Notification here or handled by Event
    }

    public function annulerReservation(string $idReservation, TypeActeurAnnulation $acteur): void
    {
        $reservation = $this->repository->findById($idReservation);
        
        // PK_PAIEMENTS STUB: Remboursement logic here

        $this->statutService->appliquerTransition($reservation, \App\Enums\StatutReservation::ANNULEE);
        $reservation->update(['acteur_annulation' => $acteur]);
        $this->minuterieService->annulerMinuterie($idReservation);
        
        event(new ReservationAnnulee($reservation));
    }

    public function setStatutEnCours(string $idReservation): void
    {
        $reservation = $this->repository->findById($idReservation);
        $this->statutService->appliquerTransition($reservation, \App\Enums\StatutReservation::EN_COURS);
    }
    
    public function consulterDemandesEnAttente(string $idHote)
    {
        return $this->repository->getEnAttenteByHote($idHote);
    }
}
