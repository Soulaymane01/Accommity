<?php

namespace App\Repositories\Eloquent;

use App\Models\Reservations\Reservation;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Enums\StatutReservation;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function findById(string $id): ?Reservation
    {
        return Reservation::find($id);
    }

    public function create(array $data): Reservation
    {
        return Reservation::create($data);
    }

    public function update(string $id, array $data): bool
    {
        $reservation = $this->findById($id);
        if ($reservation) {
            return $reservation->update($data);
        }
        return false;
    }

    public function delete(string $id): bool
    {
        $reservation = $this->findById($id);
        if ($reservation) {
            return $reservation->delete();
        }
        return false;
    }

    public function findByHote(string $idHote)
    {
        return Reservation::where('id_hote', $idHote)->get();
    }

    public function findByVoyageur(string $idVoyageur)
    {
        return Reservation::where('id_voyageur', $idVoyageur)->get();
    }

    public function getEnAttenteByHote(string $idHote)
    {
        return Reservation::where('id_hote', $idHote)
            ->where('statut', StatutReservation::EN_ATTENTE)
            ->get();
    }
}
