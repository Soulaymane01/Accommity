<?php

namespace App\Policies;

use App\Models\Utilisateurs\User;
use App\Models\Reservations\Reservation;

class ReservationPolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Reservation $reservation)
    {
        return $user->id_utilisateur === $reservation->id_voyageur ||
               $user->id_utilisateur === $reservation->id_hote;
    }

    public function create(User $user)
    {
        return $user->est_voyageur || $user->est_hote;
    }

    public function cancel(User $user, Reservation $reservation)
    {
        return $user->id_utilisateur === $reservation->id_voyageur ||
               $user->id_utilisateur === $reservation->id_hote;
    }

    public function accept(User $user, Reservation $reservation)
    {
        return $user->id_utilisateur === $reservation->id_hote;
    }
}
