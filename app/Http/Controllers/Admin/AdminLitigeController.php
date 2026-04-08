<?php

namespace App\Http\Controllers\Admin;

use App\Models\Administration\TicketLitige;
use App\Enums\TicketLitigeStatut;
use Illuminate\Http\Request;

class AdminLitigeController
{
    /**
     * GET /admin/litiges
     * Liste de tous les tickets de litige.
     */
    public function index()
    {
        $litiges = TicketLitige::getLitiges();
        $stats   = TicketLitige::getStatsLitiges();

        return view('admin.litiges.index', compact('litiges', 'stats'));
    }

    /**
     * GET /admin/litiges/{id}
     * Détail d'un ticket de litige.
     */
    public function show(string $ticketId)
    {
        $litige = TicketLitige::getLitigeById($ticketId);

        if (!$litige) {
            return redirect()->route('admin.litiges.index')->with('error', 'Ticket non trouvé.');
        }

        return view('admin.litiges.show', compact('litige'));
    }

    /**
     * PUT /admin/litiges/{id}
     * Modifier le statut d'un litige (ex: clôturer).
     */
    public function modifierStatut(Request $request, string $ticketId)
    {
        $request->validate([
            'statut' => 'required|in:En cours,Clôturé',
        ]);

        $litige = TicketLitige::findOrFail($ticketId);
        $litige->modifierStatutLitige(TicketLitigeStatut::from($request->statut));

        return redirect()->route('admin.litiges.index')->with('success', 'Le statut du litige a été mis à jour.');
    }
}
