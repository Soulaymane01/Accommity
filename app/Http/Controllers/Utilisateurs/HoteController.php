<?php

namespace App\Http\Controllers\Utilisateurs;

use App\Models\Annonces\Annonce;
use App\Models\Reservations\Reservation;
use App\Models\Paiements\Versement;
use Illuminate\Http\Request;

class HoteController
{
    /**
     * Tableau de bord du Hôte.
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $idHote = $user->id_utilisateur;

        // Stats
        $nbAnnonces = Annonce::where('id_hote', $idHote)->where('statut', 'Publié')->count();
        $nbReservations = Reservation::where('id_hote', $idHote)->count();

        // Revenus: total des versements terminés
        $totalRevenus = Versement::where('id_hote', $idHote)
            ->where('statut', 'traite')
            ->sum('montant');

        // Dernières reservations
        $dernieresReservations = Reservation::where('id_hote', $idHote)
            ->with(['annonce', 'voyageur'])
            ->orderBy('date_creation', 'desc')
            ->take(5)
            ->get();

        // Versements du hôte
        $versements = Versement::where('id_hote', $idHote)
            ->with(['reservation.annonce'])
            ->orderBy('date_versement', 'desc')
            ->take(5)
            ->get();

        return view('hote.dashboard', compact(
            'nbAnnonces',
            'nbReservations',
            'totalRevenus',
            'dernieresReservations',
            'versements'
        ));
    }
}
