<?php

namespace App\Services\Paiements;

use App\Models\Paiements\Remboursement;
use App\Models\Paiements\Paiement;
use App\Models\Paiements\Recu;
use App\Models\Reservations\Reservation;

use App\Enums\TypeActeurAnnulation;
use App\Enums\MotifRemboursement;
use App\Services\Reservations\ReservationService;
use Illuminate\Support\Str;

class RemboursementService
{
    public function traiterAnnulation(Reservation $reservation, TypeActeurAnnulation $acteur, float $montantCalcule, string $motifCalcule): ?Remboursement
    {
        // Find the original payment
        $paiement = Paiement::where('id_reservation', $reservation->id_reservation)
            ->where('statut', \App\Enums\StatutPaiement::REUSSI)
            ->first();

        if (!$paiement) {
            // Cannot refund if they never paid (e.g. pending requests or unpaid instant bookings)
            return null;
        }

        // Calculate refund amount based on cancellation policy
        if ($acteur === TypeActeurAnnulation::HOTE) {
            $montantRemboursement = $paiement->montant;
            $motif = MotifRemboursement::ANNULATION_HOTE;
        } else {
            $montantRemboursement = $montantCalcule;
            $motif = MotifRemboursement::ANNULATION_VOYAGEUR;
        }

        if ($montantRemboursement <= 0) {
            return null;
        }

        // Execute Refund Simulation
        $remboursement = Remboursement::create([
            'id_remboursement' => Str::uuid()->toString(),
            'id_reservation' => $reservation->id_reservation,
            'id_voyageur' => $reservation->id_voyageur,
            'montant' => $montantRemboursement,
            'motif' => $motif,
            'date_remboursement' => now()
        ]);

        // Mark corresponding payment as refunded
        $statutPaiement = $montantRemboursement == $paiement->montant 
                        ? \App\Enums\StatutPaiement::REMBOURSE 
                        : \App\Enums\StatutPaiement::REUSSI; 
        
        if ($statutPaiement === \App\Enums\StatutPaiement::REMBOURSE) {
            $paiement->update(['statut' => $statutPaiement]);
        }

        return $remboursement;
    }
}
