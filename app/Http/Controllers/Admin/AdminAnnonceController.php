<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Annonces\Annonce;
use App\Models\Utilisateurs\User;
use App\Models\Notifications\Notification;
use App\Enums\StatutAnnonce;
use App\Enums\TypeAlerte;

class AdminAnnonceController
{
    /**
     * Affiche la liste des annonces avec filtrage
     */
    public function index(Request $request)
    {
        $critere = $request->input('critere', 'tous');
        $searchQuery = $request->input('search');
        $selectedId = $request->input('selected');
        
        $annonces = Annonce::filtrerAnnonces($critere);
        
        // Manual search filtering on top of DB filtering if a name/titre is supplied
        if ($searchQuery) {
            $annonces = Annonce::with('hote')->where(function($query) use ($searchQuery) {
                $query->where('titre', 'ilike', '%' . $searchQuery . '%')
                      ->orWhereHas('hote', function($q) use ($searchQuery) {
                          $q->where('nom', 'ilike', '%' . $searchQuery . '%')
                            ->orWhere('prenom', 'ilike', '%' . $searchQuery . '%');
                      });
            })->latest('date_creation')->paginate(10);
        }
        
        $selectedAnnonce = null;
        if ($selectedId) {
            $selectedAnnonce = Annonce::getAnnonceById($selectedId);
        }

        return view('admin.annonces.index', compact('annonces', 'critere', 'searchQuery', 'selectedAnnonce'));
    }

    /**
     * Confirmer et Publier l'Annonce
     */
    public function publish($id)
    {
        $annonce = Annonce::getAnnonceById($id);
        if ($annonce) {
            $annonce->setStatutAnnonce(StatutAnnonce::PUBLIE);
            
            // Envoyer la notification
            Notification::create([
                'id_utilisateur' => $annonce->id_hote,
                'titre' => 'Annonce Publiée !',
                'contenu' => 'Votre annonce "' . $annonce->titre . '" a été approuvée et publiée par l\'administrateur.',
                'type_alerte' => TypeAlerte::Systeme,
                'est_lue' => false,
                'date_creation' => now(),
            ]);
        }
        return redirect()->back()->with('success_dialog', 'L\'annonce a été publiée avec succès.');
    }

    /**
     * Suspendre l'Annonce
     */
    public function suspend($id)
    {
        $annonce = Annonce::getAnnonceById($id);
        if ($annonce) {
            $annonce->suspendre();
            
            // Envoyer la notification
            Notification::create([
                'id_utilisateur' => $annonce->id_hote,
                'titre' => 'Annonce Suspendue',
                'contenu' => 'Votre annonce "' . $annonce->titre . '" a été suspendue temporairement par l\'administrateur.',
                'type_alerte' => TypeAlerte::Systeme,
                'est_lue' => false,
                'date_creation' => now(),
            ]);
        }
        return redirect()->back()->with('success_dialog', 'L\'annonce a été suspendue.');
    }
    
    /**
     * Rejeter l'Annonce
     */
    public function reject(Request $request, $id)
    {
        $annonce = Annonce::getAnnonceById($id);
        if ($annonce) {
            $annonce->setStatutAnnonce(StatutAnnonce::REJETE);
            
            // Envoyer la notification
            Notification::create([
                'id_utilisateur' => $annonce->id_hote,
                'titre' => 'Annonce Rejetée',
                'contenu' => 'Votre annonce "' . $annonce->titre . '" a été rejetée car l\'administrateur a estimé qu\'elle ne respecte pas les critères de la plateforme.',
                'type_alerte' => TypeAlerte::Systeme,
                'est_lue' => false,
                'date_creation' => now(),
            ]);
        }
        return redirect()->back()->with('success_dialog', 'L\'annonce a été rejetée définitivement.');
    }
}
