<?php

namespace App\Services\Paiements;

use App\Models\Paiements\Versement;
use App\Models\Reservations\Reservation;
use App\Enums\StatutVersement;
use Illuminate\Support\Str;

class VersementService
{
    public function initierVersement(Reservation $reservation): ?Versement
    {
        // 1. Check if checkout has passed safely. Normally called via Cron Job or manually.
        
        // 2. Calculate Host earnings. Payment = Total - Frais Service
        $montantHote = $reservation->montant_total - $reservation->frais_service;

        // 3. Init payout
        $versement = Versement::create([
            'id_versement' => Str::uuid()->toString(),
            'id_hote' => $reservation->id_hote,
            'id_reservation' => $reservation->id_reservation,
            'montant' => $montantHote,
            'statut' => StatutVersement::EN_ATTENTE,
            'date_versement' => now(),
            'reference_bancaire' => 'IBAN_SECURE_MASKED' // Would come from Host Profil setup
        ]);

        return $versement;
    }
}
