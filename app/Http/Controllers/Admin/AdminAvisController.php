<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Evaluations\Evaluation;
use App\Models\Notifications\Notification;
use App\Enums\TypeAlerte;

class AdminAvisController
{
    /**
     * Affiche la liste des avis signalés
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');
        $selectedId = $request->input('selected');
        
        $query = Evaluation::with(['auteur', 'cible', 'reservation.annonce'])
                           ->where('est_signale', true)
                           ->latest('date_creation');
        
        // Manual search filtering
        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('commentaire', 'ilike', '%' . $searchQuery . '%')
                  ->orWhere('motif_signalement', 'ilike', '%' . $searchQuery . '%')
                  ->orWhereHas('auteur', function($qAuteur) use ($searchQuery) {
                      $qAuteur->where('nom', 'ilike', '%' . $searchQuery . '%')
                              ->orWhere('prenom', 'ilike', '%' . $searchQuery . '%');
                  });
            });
        }
        
        $avis = $query->paginate(10);
        
        $selectedAvis = null;
        if ($selectedId) {
            $selectedAvis = Evaluation::getAvisById($selectedId);
        }

        return view('admin.avis_signales.index', compact('avis', 'searchQuery', 'selectedAvis'));
    }

    /**
     * Supprime définitivement l'avis
     */
    public function delete(Request $request, $id)
    {
        $avis = Evaluation::getAvisById($id);
        
        if ($avis) {
            // Sauvegarder les ID avant suppression pour la notification
            $idAuteur = $avis->id_auteur;
            $idCible = $avis->id_cible;
            
            $avis->supprimerAvis();
            
            // Notifier l'auteur
            if ($idAuteur) {
                Notification::create([
                    'id_utilisateur' => $idAuteur,
                    'titre' => 'Avis supprimé par la modération',
                    'contenu' => 'Votre avis a été supprimé suite à un signalement, car il ne respectait pas nos conditions d\'utilisation.',
                    'type_alerte' => TypeAlerte::Systeme,
                    'est_lue' => false,
                    'date_creation' => now(),
                ]);
            }
            
            // Notifier la cible (qui a probablement fait le signalement)
            if ($idCible) {
                Notification::create([
                    'id_utilisateur' => $idCible,
                    'titre' => 'Signalement traité',
                    'contenu' => 'L\'avis que vous avez signalé a été retiré de la plateforme par nos équipes.',
                    'type_alerte' => TypeAlerte::Systeme,
                    'est_lue' => false,
                    'date_creation' => now(),
                ]);
            }
        }
        
        return redirect()->route('admin.avis_signales.index')->with('success_dialog', 'L\'avis a été supprimé définitivement.');
    }

    /**
     * Conserve l'avis (rejeter le signalement)
     */
    public function keep(Request $request, $id)
    {
        $avis = Evaluation::getAvisById($id);
        
        if ($avis) {
            $avis->conserverAvis();
            
            // On peut notifier la cible (qui a fait le signalement)
            if ($avis->id_cible) {
                Notification::create([
                    'id_utilisateur' => $avis->id_cible,
                    'titre' => 'Signalement rejeté',
                    'contenu' => 'Après examen, nos équipes ont estimé que l\'avis signalé ne violait pas nos règles. L\'avis a été conservé.',
                    'type_alerte' => TypeAlerte::Systeme,
                    'est_lue' => false,
                    'date_creation' => now(),
                ]);
            }
        }
        
        return redirect()->route('admin.avis_signales.index')->with('success_dialog', 'L\'avis a été conservé. Le signalement a été annulé.');
    }
}
