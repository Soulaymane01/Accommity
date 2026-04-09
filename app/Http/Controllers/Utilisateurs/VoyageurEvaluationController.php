<?php

namespace App\Http\Controllers\Utilisateurs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Evaluations\Evaluation;
use App\Models\Reservations\Reservation;

class VoyageurEvaluationController
{
    /**
     * Affiche l'espace "Mes Évaluations" (Laissées et Reçues)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        // On gère les onglets (laissées par défaut)
        $tab = $request->input('tab', 'donnees');
        $action = $request->input('action');
        $idAvis = $request->input('id');

        $evaluationsDonnees = [];
        $evaluationsRecues = [];

        if ($tab === 'donnees') {
            $evaluationsDonnees = Evaluation::getEvaluations($user->id_utilisateur);
        } else {
            $evaluationsRecues = Evaluation::listerEvaluation($user->id_utilisateur);
        }

        // Pour peupler la modal dynamique
        $selectedEvaluation = null;
        if ($idAvis) {
            $selectedEvaluation = Evaluation::with('details')->find($idAvis);
        }

        return view('voyageur.evaluations.index', compact('tab', 'action', 'evaluationsDonnees', 'evaluationsRecues', 'selectedEvaluation'));
    }

    /**
     * Crée une nouvelle évaluation
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_reservation' => 'required|uuid',
            'commentaire' => 'required|string|max:1000',
            'proprete' => 'required|numeric|min:1|max:5',
            'communication' => 'required|numeric|min:1|max:5',
            'emplacement' => 'required|numeric|min:1|max:5',
            'rapport_qualite_prix' => 'required|numeric|min:1|max:5',
            'exactitude' => 'required|numeric|min:1|max:5',
        ]);

        $user = Auth::user();
        $reservation = Reservation::findOrFail($request->id_reservation);

        // Vérifier que le voyageur est bien le créateur de cette réservation
        if ($reservation->id_voyageur !== $user->id_utilisateur) {
            abort(403, "Action non autorisée.");
        }

        try {
            $notesDetaillees = $request->only(['proprete', 'communication', 'emplacement', 'rapport_qualite_prix', 'exactitude']);
            
            Evaluation::creer(
                $user->id_utilisateur, 
                $reservation->annonce->id_hote, 
                $reservation->id_reservation, 
                $reservation->id_annonce, 
                $request->commentaire, 
                $notesDetaillees
            );

            return redirect()->back()->with('success_dialog', 'Votre évaluation a été publiée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['erreur' => $e->getMessage()]);
        }
    }

    /**
     * Modifie une évaluation existante
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'commentaire' => 'required|string|max:1000',
            'proprete' => 'required|numeric|min:1|max:5',
            'communication' => 'required|numeric|min:1|max:5',
            'emplacement' => 'required|numeric|min:1|max:5',
            'rapport_qualite_prix' => 'required|numeric|min:1|max:5',
            'exactitude' => 'required|numeric|min:1|max:5',
        ]);

        $evaluation = Evaluation::findOrFail($id);
        
        // Seul l'auteur peut modifier
        if ($evaluation->id_auteur !== Auth::id()) {
            abort(403);
        }

        try {
            $notesDetaillees = $request->only(['proprete', 'communication', 'emplacement', 'rapport_qualite_prix', 'exactitude']);
            $evaluation->modifier($request->commentaire, $notesDetaillees);

            return redirect()->route('voyageur.evaluations.index', ['tab' => 'donnees'])->with('success_dialog', 'Votre évaluation a été modifiée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['erreur' => "Erreur lors de la modification."]);
        }
    }

    /**
     * Supprime une évaluation
     */
    public function destroy($id)
    {
        $evaluation = Evaluation::findOrFail($id);
        
        // Seul l'auteur peut supprimer
        if ($evaluation->id_auteur !== Auth::id()) {
            abort(403);
        }

        $evaluation->supprimer();

        return redirect()->route('voyageur.evaluations.index', ['tab' => 'donnees'])->with('success_dialog', 'L\'avis a été supprimé.');
    }

    /**
     * Signaler une évaluation reçue
     */
    public function signaler(Request $request, $id)
    {
        $request->validate([
            'motif' => 'required|string|max:500'
        ]);

        // Vérifier que je suis bien la cible de l'évaluation pour pouvoir la signaler
        $evaluation = Evaluation::findOrFail($id);
        if ($evaluation->id_cible !== Auth::id()) {
            abort(403, "Vous ne pouvez signaler que les avis que vous avez reçus.");
        }

        // Marque comme signalée coté Evaluation (pour le flag)
        $evaluation->signalerEvaluation($request->motif);

        // Génère le Ticket pour l'Admin
        $evaluation->genererTicket(Auth::id(), $request->motif);

        return redirect()->route('voyageur.evaluations.index', ['tab' => 'recues'])->with('success_dialog', 'Le commentaire a été signalé à l\'administration avec succès.');
    }
}
