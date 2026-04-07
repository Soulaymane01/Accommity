<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;

class AdminDashboardController
{
    /**
     * Show the application admin dashboard.
     */
    public function index()
    {
        // 1. UTILISATEURS
        $statsUtilisateurs = [
            'hotes' => DB::table('utilisateurs')->where('est_hote', true)->count(),
            'voyageurs' => DB::table('utilisateurs')->where('est_voyageur', true)->count(),
        ];

        // 2. ANNONCES
        $statsAnnonces = [
            'total' => DB::table('annonces')->count(),
            'publiees' => DB::table('annonces')->where('statut', 'Publié')->count(),
            'en_attente' => DB::table('annonces')->where('statut', 'En cours de vérification')->count(),
            'suspendues' => DB::table('annonces')->where('statut', 'Suspendu')->count(),
        ];

        // 3. RESERVATIONS
        $statsReservations = [
            'en_cours' => DB::table('reservations')->where('statut', 'En cours')->count(),
            'confirmees' => DB::table('reservations')->where('statut', 'Confirmée')->count(),
            'terminees' => DB::table('reservations')->where('statut', 'Terminée')->count(),
            'annulees' => DB::table('reservations')->where('statut', 'Annulée')->count(),
            'refusees' => DB::table('reservations')->where('statut', 'Refusée')->count(),
        ];

        // 4. TRANSACTIONS (Paiements, Versements, Remboursements)
        $statsTransactions = [
            'paiements_count' => DB::table('paiements')->where('statut', 'reussi')->count(),
            'paiements_total' => DB::table('paiements')->where('statut', 'reussi')->sum('montant'),
            
            'versements_count' => DB::table('versements')->where('statut', 'traite')->count(),
            'versements_total' => DB::table('versements')->where('statut', 'traite')->sum('montant'),

            'remboursements_count' => DB::table('remboursements')->count(),
            'remboursements_total' => DB::table('remboursements')->sum('montant'),
        ];

        // 5. AVIS SIGNALES
        $statsAvis = [
            'signales' => DB::table('evaluations')->where('est_signale', true)->count(),
        ];

        // 6. LITIGES
        $statsLitiges = [
            'resolus' => DB::table('ticket_litiges')->where('statut', 'Résolu')->count(),
            'en_cours' => DB::table('ticket_litiges')->where('statut', 'En cours')->count(),
            'clotures' => DB::table('ticket_litiges')->where('statut', 'Clôturé')->count(),
        ];

        return view('admin.dashboard', compact(
            'statsUtilisateurs',
            'statsAnnonces',
            'statsReservations',
            'statsTransactions',
            'statsAvis',
            'statsLitiges'
        ));
    }
}
