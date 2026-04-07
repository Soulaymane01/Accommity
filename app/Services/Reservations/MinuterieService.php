<?php

namespace App\Services\Reservations;

use App\Models\Reservations\Minuterie;
use Carbon\Carbon;

class MinuterieService
{
    /**
     * Démarre une minuterie de 24h pour l'expiration de la demande.
     */
    public function demarrerMinuterie24h(string $idReservation): void
    {
        Minuterie::create([
            'id_reservation' => $idReservation,
            'type_minuterie' => 'expiration_demande',
            'date_echeance' => Carbon::now()->addHours(24),
            'est_active' => true,
        ]);
    }

    /**
     * Annule une minuterie active.
     */
    public function annulerMinuterie(string $idReservation): void
    {
        Minuterie::where('id_reservation', $idReservation)
            ->where('est_active', true)
            ->update(['est_active' => false]);
    }

    /**
     * Vérifie les minuteries expirées et retourne les IDS des réservations associées.
     */
    public function getDemandesExpirees(): array
    {
        return Minuterie::where('est_active', true)
            ->where('type_minuterie', 'expiration_demande')
            ->where('date_echeance', '<=', Carbon::now())
            ->pluck('id_reservation')
            ->toArray();
    }
}
