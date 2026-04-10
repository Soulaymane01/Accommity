<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Paiements\Paiement;
use App\Models\Paiements\Versement;
use App\Models\Paiements\Remboursement;

class AdminFinanceController
{
    /**
     * Voir la liste des paiements (Voyageur -> App)
     */
    public function paiements(Request $request)
    {
        $statut = $request->input('statut', 'tous');
        $paiements = Paiement::filtrerPaiement($statut);
        
        $selectedPaiement = null;
        if ($request->has('id')) {
            $selectedPaiement = Paiement::getPaiementById($request->id);
        }

        return view('admin.transactions.paiements', compact('paiements', 'statut', 'selectedPaiement'));
    }

    /**
     * Voir la liste des versements (App -> Hôte)
     */
    public function versements(Request $request)
    {
        $statut = $request->input('statut', 'tous');
        $versements = Versement::filtrerVersement($statut);
        
        $selectedVersement = null;
        if ($request->has('id')) {
            $selectedVersement = Versement::getVersementById($request->id);
        }

        return view('admin.transactions.versements', compact('versements', 'statut', 'selectedVersement'));
    }

    /**
     * Voir la liste des remboursements (App -> Voyageur)
     */
    public function remboursements(Request $request)
    {
        $motif = $request->input('motif', 'tous');
        $remboursements = Remboursement::filtrerRemboursement($motif);
        
        $selectedRemboursement = null;
        if ($request->has('id')) {
            $selectedRemboursement = Remboursement::getRemboursementById($request->id);
        }

        return view('admin.transactions.remboursements', compact('remboursements', 'motif', 'selectedRemboursement'));
    }
}
