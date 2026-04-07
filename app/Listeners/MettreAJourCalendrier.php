<?php

namespace App\Listeners;

use App\Events\ReservationConfirmee;
use App\Models\Annonces\Calendrier;

class MettreAJourCalendrier
{
    public function handle(ReservationConfirmee $event): void
    {
        $reservation = $event->reservation;

        // RG19: Une réservation confirmée bloque automatiquement les dates
        // On suppose que MettreAJourCalendrier utilise le modele Calendrier ou un service CalendrierService.
        
        // This is a stub implementation calling a hypothetical method on Calendrier model.
        // In a strictly decoupled package, you would call Annonce package's API or emit another event.
        
        $calendrier = Calendrier::firstOrCreate(
            ['id_annonce' => $reservation->id_annonce],
            [
                'date_debut' => $reservation->date_arrivee,
                'date_fin' => $reservation->date_depart,
                'est_disponible' => true,
                'type_blockage' => 'Disponible'
            ]
        );
        
        // Block the dates functionally
        if (method_exists($calendrier, 'bloquerDatesManuel')) {
            $calendrier->bloquerDatesManuel($reservation->date_arrivee, $reservation->date_depart);
        } else {
            // Primitive block
            $calendrier->update([
                'est_disponible' => false,
                'type_blockage' => 'Bloque Reservation'
            ]);
        }
    }
}
