<?php

namespace App\Http\Controllers\Reservations;

use App\Http\Requests\Reservations\StoreReservationRequest;
use App\Services\Reservations\ReservationService;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Enums\TypeActeurAnnulation;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReservationController
{
    use AuthorizesRequests;

    protected $reservationService;
    protected $repository;

    public function __construct(ReservationService $reservationService, ReservationRepositoryInterface $repository)
    {
        $this->reservationService = $reservationService;
        $this->repository = $repository;
    }

    // Vue: "Mes Voyages" (Dashboard Voyageur)
    public function mesVoyages(Request $request)
    {
        $reservations = \App\Models\Reservations\Reservation::where('id_voyageur', $request->user()->id_utilisateur)
                        ->with('annonce')
                        ->orderBy('date_creation', 'desc')
                        ->get();
                        
        return view('voyageur.reservations.index', compact('reservations'));
    }

    // Vue: "Demandes de réservation" (Dashboard Hôte)
    public function demandes(Request $request)
    {
        $query = \App\Models\Reservations\Reservation::where('id_hote', $request->user()->id_utilisateur)
                        ->with(['annonce', 'voyageur'])
                        ->orderBy('date_creation', 'desc');

        if ($request->has('id_annonce')) {
            $query->where('id_annonce', $request->id_annonce);
        }

        $reservations = $query->get();
                        
        return view('hote.reservations.index', compact('reservations'));
    }

    public function store(StoreReservationRequest $request)
    {
        $this->authorize('create', \App\Models\Reservations\Reservation::class);

        $dates = [
            'dateArrivee' => $request->date_arrivee,
            'dateDepart'  => $request->date_depart
        ];

        try {
            $reservation = $this->reservationService->soumettreDemande(
                $request->id_annonce,
                $dates,
                $request->nb_voyageurs,
                $request->message_optionnel
            );

            // Both modes go through the payment page.
            // The outcome (Confirmée vs En attente) is decided AFTER payment.
            return redirect()->route('reservations.payment', $reservation->id_reservation);

        } catch (\Exception $e) {
            return back()->with('error_dialog', $e->getMessage());
        }
    }

    /**
     * Show the payment page for a pending reservation.
     */
    public function showPayment($id)
    {
        $reservation = $this->repository->findById($id);
        $this->authorize('pay', $reservation);

        // Load the annonce for display
        $reservation->load(['annonce', 'hote']);

        return view('voyageur.reservations.payment', compact('reservation'));
    }

    /**
     * Process the payment and confirm the reservation.
     */
    public function processPayment(Request $request, $id)
    {
        $reservation = $this->repository->findById($id);
        $this->authorize('pay', $reservation);

        $request->validate([
            'methode_paiement' => 'required|in:carte_bancaire,paypal',
        ]);

        try {
            if ($reservation->mode_reservation === \App\Enums\ModeReservation::INSTANTANEE) {
                // Instant booking: confirm and pay NOW
                $this->reservationService->confirmerEtPayer($id, $request->methode_paiement);

                return redirect()->route('voyageur.reservations.index')
                    ->with('success_dialog', 'Paiement effectué ! Votre réservation est confirmée. 🎉');
            } else {
                // Demande: record payment but keep reservation EN_ATTENTE
                // The host still needs to accept/refuse before it's confirmed
                $this->reservationService->enregistrerPaiementDemande($id, $request->methode_paiement);

                return redirect()->route('voyageur.reservations.index')
                    ->with('success_dialog', 'Paiement enregistré ! Votre demande a été envoyée à l\'hôte. Vous serez notifié(e) dès qu\'il aura répondu. 📬');
            }
        } catch (\Exception $e) {
            return back()->with('error_dialog', $e->getMessage());
        }
    }

    public function accept($id)
    {
        $reservation = $this->repository->findById($id);
        $this->authorize('accept', $reservation);

        try {
            $this->reservationService->accepterDemande($id);
            return back()->with('success_dialog', 'Réservation acceptée. Le calendrier a été mis à jour.');
        } catch (\Exception $e) {
            return back()->with('error_dialog', $e->getMessage());
        }
    }

    public function refuse(Request $request, $id)
    {
        $reservation = $this->repository->findById($id);
        $this->authorize('accept', $reservation); 
        
        try {
            $this->reservationService->refuserDemande($id, $request->input('motif', 'Non spécifié'));
            return back()->with('success_dialog', 'Réservation refusée.');
        } catch (\Exception $e) {
            return back()->with('error_dialog', $e->getMessage());
        }
    }

    public function apercuAnnulation($id)
    {
        $reservation = $this->repository->findById($id);
        $this->authorize('cancel', $reservation);

        $details = $this->reservationService->calculerApercuAnnulation($id);

        return view('voyageur.reservations.cancel_preview', compact('reservation', 'details'));
    }

    public function cancel(Request $request, $id)
    {
        $reservation = $this->repository->findById($id);
        $this->authorize('cancel', $reservation);
        
        // Determine whether it is the Voyageur or the Hote canceling it
        $acteur = $request->user()->id_utilisateur === $reservation->id_voyageur 
                 ? TypeActeurAnnulation::VOYAGEUR 
                 : TypeActeurAnnulation::HOTE;
                 
        try {
            $this->reservationService->annulerReservation($id, $acteur);
            
            if ($acteur === TypeActeurAnnulation::VOYAGEUR) {
                return redirect()->route('voyageur.dashboard')->with('success_dialog', 'Réservation annulée avec succès.');
            }
            
            return back()->with('success_dialog', 'Réservation annulée avec succès.');
        } catch (\Exception $e) {
             return back()->with('error_dialog', $e->getMessage());
        }
    }
}
