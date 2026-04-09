<?php

namespace App\Services\Paiements;

use App\Models\Paiements\Paiement;
use App\Models\Paiements\Recu;
use App\Models\Reservations\Reservation;
use App\Enums\StatutPaiement;

use Illuminate\Support\Str;

class PaiementService
{
    public function effectuerPaiement(Reservation $reservation, float $montant, string $methode): Paiement
    {
        // Simulated Payment Gateway logic (Stripe, Paypal, etc.)
        // For the sake of UML integration, we assume success.

        $paiement = Paiement::create([
            'id_paiement' => Str::uuid()->toString(),
            'id_reservation' => $reservation->id_reservation,
            'id_voyageur' => $reservation->id_voyageur,
            'montant' => $montant,
            'statut' => StatutPaiement::REUSSI,
            'date_transaction' => now(),
            'methode_paiement' => $methode
        ]);

        // Generate the receipt for this payment (RG26)
        $this->genererRecu($paiement);

        return $paiement;
    }

    public function genererRecu(Paiement $paiement): Recu
    {
        // Simulation of PDF generation. In real app, we'd use Snappy or DomPDF
        $fakePdfUrl = '/storage/recus/recu_' . $paiement->id_paiement . '.pdf';
        $numFacture = 'F-' . date('Y') . '-' . strtoupper(Str::random(5));

        return Recu::create([
            'id_recu' => Str::uuid()->toString(),
            'id_paiement' => $paiement->id_paiement,
            'numero_facture' => $numFacture,
            'chemin_pdf' => $fakePdfUrl,
            'date_generation' => now()
        ]);
    }
}
