<?php

namespace App\Http\Controllers\Utilisateurs;

use App\Models\Reservations\Reservation;
use App\Models\Annonces\Annonce;
use App\Models\Paiements\Paiement;
use App\Models\Paiements\Remboursement;
use Illuminate\Http\Request;

class VoyageurController
{
    /**
     * Tableau de bord du Voyageur.
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        // Réservations à venir
        $prochainsVoyages = Reservation::where('id_voyageur', $user->id_utilisateur)
            ->whereIn('statut', ['En attente', 'Confirmée'])
            ->with(['annonce', 'hote'])
            ->orderBy('date_arrivee', 'asc')
            ->take(3)
            ->get();

        // Annonces recommandées
        $recommandations = Annonce::where('statut', 'Publié')
            ->orderBy('date_creation', 'desc')
            ->take(4)
            ->get();

        // Paiements du voyageur
        $paiements = Paiement::where('id_voyageur', $user->id_utilisateur)
            ->with(['reservation.annonce'])
            ->orderBy('date_transaction', 'desc')
            ->take(5)
            ->get();

        // Remboursements du voyageur
        $remboursements = Remboursement::where('id_voyageur', $user->id_utilisateur)
            ->with(['reservation.annonce'])
            ->orderBy('date_remboursement', 'desc')
            ->take(5)
            ->get();

        return view('voyageur.dashboard', compact('prochainsVoyages', 'recommandations', 'paiements', 'remboursements'));
    }
}
