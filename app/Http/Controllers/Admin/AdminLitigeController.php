<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Administration\TicketLitige;
use App\Models\Notifications\Notification;
use App\Enums\TypeAlerte;

class AdminLitigeController
{
    /**
     * Affiche la liste des litiges
     */
    public function index(Request $request)
    {
        $critere = $request->input('critere', 'tous');
        $searchQuery = $request->input('search');
        $selectedId = $request->input('selected');
        
        $litiges = TicketLitige::filtrerLitiges($critere);
        
        // Manual search filtering on top of DB filtering if a subject or booking reference is supplied
        if ($searchQuery) {
            $litiges = TicketLitige::with(['evaluation.reservation', 'declarant'])->where(function($query) use ($searchQuery) {
                $query->where('motif', 'ilike', '%' . $searchQuery . '%')
                      ->orWhereHas('declarant', function($q) use ($searchQuery) {
                          $q->where('nom', 'ilike', '%' . $searchQuery . '%')
                            ->orWhere('prenom', 'ilike', '%' . $searchQuery . '%');
                      });
            })->latest('date_creation')->paginate(10);
        }
        
        $selectedLitige = null;
        if ($selectedId) {
            $selectedLitige = TicketLitige::getLitigeById($selectedId);
        }

        return view('admin.litiges.index', compact('litiges', 'critere', 'searchQuery', 'selectedLitige'));
    }

    /**
     * Clôturer le litige et notifier les deux parties
     */
    public function close(Request $request, $id)
    {
        $litige = TicketLitige::getLitigeById($id);
        
        if ($litige) {
            $litige->cloturerLitige();
            
            $decision = $request->input('decision', 'L\'administrateur a clôturé le litige de manière définitive.');
            
            // On veut notifier le déclarant et la "cible" de l'évaluation
            // Check si l'évaluation existe
            if ($litige->evaluation) {
                // Notifier le déclarant
                if ($litige->id_declarant) {
                    Notification::create([
                        'id_utilisateur' => $litige->id_declarant,
                        'titre' => 'Résolution de litige suite à l\'évaluation',
                        'contenu' => 'Votre litige ("' . $litige->motif . '") a été arbitré et clôturé par le support. Décision/Message: ' . $decision,
                        'type_alerte' => TypeAlerte::Systeme,
                        'est_lue' => false,
                        'date_creation' => now(),
                    ]);
                }
                
                // Notifier la cible de l'évaluation
                if ($litige->evaluation->id_cible) {
                    Notification::create([
                        'id_utilisateur' => $litige->evaluation->id_cible,
                        'titre' => 'Résolution de litige suite à l\'évaluation',
                        'contenu' => 'Le litige déclaré contre vous concernant une évaluation ("' . $litige->motif . '") a été arbitré et clôturé par le support. Décision/Message: ' . $decision,
                        'type_alerte' => TypeAlerte::Systeme,
                        'est_lue' => false,
                        'date_creation' => now(),
                    ]);
                }
            }
        }
        
        return redirect()->back()->with('success_dialog', 'Le litige a été clôturé et les parties concernées ont été notifiées.');
    }
}
