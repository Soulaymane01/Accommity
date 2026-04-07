<?php

namespace App\Http\Controllers\Reservations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservations\StoreReservationRequest;
use App\Http\Resources\Reservations\ReservationResource;
use App\Services\Reservations\ReservationService;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Enums\TypeActeurAnnulation;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReservationController extends Controller
{
    use AuthorizesRequests;

    protected $reservationService;
    protected $repository;

    public function __construct(ReservationService $reservationService, ReservationRepositoryInterface $repository)
    {
        $this->reservationService = $reservationService;
        $this->repository = $repository;
    }

    public function store(StoreReservationRequest $request)
    {
        $this->authorize('create', \App\Models\Reservations\Reservation::class);

        $dates = [
            'dateArrivee' => $request->date_arrivee,
            'dateDepart' => $request->date_depart
        ];

        $reservation = $this->reservationService->soumettreDemande(
            $request->id_annonce,
            $dates,
            $request->nb_voyageurs,
            $request->message_optionnel
        );

        return new ReservationResource($reservation);
    }

    public function show($id)
    {
        $reservation = $this->repository->findById($id);
        if (!$reservation) {
            return response()->json(['message' => 'Not found'], 404);
        }
        $this->authorize('view', $reservation);
        
        return new ReservationResource($reservation);
    }

    public function accept($id)
    {
        $reservation = $this->repository->findById($id);
        $this->authorize('accept', $reservation);

        $this->reservationService->accepterDemande($id);
        
        return response()->json(['message' => 'Réservation acceptée.']);
    }

    public function refuse(Request $request, $id)
    {
        $reservation = $this->repository->findById($id);
        $this->authorize('accept', $reservation); // same permission as accept for host
        
        $this->reservationService->refuserDemande($id, $request->input('motif'));
        
        return response()->json(['message' => 'Réservation refusée.']);
    }

    public function cancel(Request $request, $id)
    {
        $reservation = $this->repository->findById($id);
        $this->authorize('cancel', $reservation);
        
        $acteur = $request->user()->id_utilisateur === $reservation->id_voyageur 
                 ? TypeActeurAnnulation::VOYAGEUR 
                 : TypeActeurAnnulation::HOTE;
                 
        $this->reservationService->annulerReservation($id, $acteur);
        
        return response()->json(['message' => 'Réservation annulée.']);
    }
}
