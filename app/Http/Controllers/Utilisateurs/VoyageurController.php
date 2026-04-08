<?php

namespace App\Http\Controllers\Utilisateurs;

use App\Models\Reservations\Reservation;
use App\Models\Annonces\Annonce;
use Illuminate\Http\Request;

class VoyageurController
{
    /**
     * Tableau de bord du Voyageur.
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        // Récupérer les réservations à venir
        $prochainsVoyages = Reservation::where('id_voyageur', $user->id_utilisateur)
            ->whereIn('statut', ['En attente', 'Confirmée'])
            ->with(['annonce', 'hote'])
            ->orderBy('date_arrivee', 'asc')
            ->take(3)
            ->get();

        // Récupérer des annonces recommandées (simulé par les plus récentes)
        $recommandations = Annonce::where('statut', 'Publié')
            ->orderBy('date_creation', 'desc')
            ->take(4)
            ->get();

        return view('voyageur.dashboard', compact('prochainsVoyages', 'recommandations'));
    }

}
