<?php

namespace App\Services\Reservations;

use App\Models\Reservations\Reservation;
use App\Enums\StatutReservation;
use RuntimeException;

class StatutService
{
    /**
     * Valide si le passage d'un statut à un autre est autorisé.
     */
    public function validerTransition(Reservation $reservation, StatutReservation $nouveauStatut): bool
    {
        $statutActuel = $reservation->statut ?? StatutReservation::EN_ATTENTE; // default fallback
        
        switch ($nouveauStatut) {
            case StatutReservation::CONFIRMEE:
                return in_array($statutActuel, [StatutReservation::EN_ATTENTE]); // Or new without state
            case StatutReservation::EN_COURS:
                return $statutActuel === StatutReservation::CONFIRMEE;
            case StatutReservation::TERMINEE:
                return $statutActuel === StatutReservation::EN_COURS;
            case StatutReservation::REFUSEE:
                return $statutActuel === StatutReservation::EN_ATTENTE;
            case StatutReservation::ANNULEE:
                return in_array($statutActuel, [StatutReservation::EN_ATTENTE, StatutReservation::CONFIRMEE]);
            case StatutReservation::EXPIREE:
                return $statutActuel === StatutReservation::EN_ATTENTE;
            default:
                return false;
        }
    }

    /**
     * Applique la transition si elle est valide, sinon jette une exception.
     */
    public function appliquerTransition(Reservation $reservation, StatutReservation $nouveauStatut): void
    {
        if (!$this->validerTransition($reservation, $nouveauStatut)) {
            throw new RuntimeException("Transition de statut invalide : " . ($reservation->statut?->value ?? 'null') . " -> " . $nouveauStatut->value);
        }

        $reservation->update(['statut' => $nouveauStatut]);
    }
}
