<?php

namespace App\Http\Controllers\Utilisateurs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Evaluations\Evaluation;
use App\Models\Reservations\Reservation;

class HoteEvaluationController
{
    /**
     * Affiche l'espace "Mes Évaluations" pour l'Hôte
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        // On gère les onglets
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

        $selectedEvaluation = null;
        if ($idAvis) {
            $selectedEvaluation = Evaluation::with('details')->find($idAvis);
        }

        return view('hote.evaluations.index', compact('tab', 'action', 'evaluationsDonnees', 'evaluationsRecues', 'selectedEvaluation'));
    }

    /**
     * L'hôte crée une évaluation sur le voyageur
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_reservation' => 'required|uuid',
            'commentaire' => 'required|string|max:1000',
            'proprete' => 'required|numeric|min:1|max:5',
            'communication' => 'required|numeric|min:1|max:5',
        ]);

        $user = Auth::user();
        $reservation = Reservation::findOrFail($request->id_reservation);

        // Vérifier que c'est bien l'hôte de cette réservation
        if ($reservation->annonce->id_hote !== $user->id_utilisateur) {
            abort(403, "Action non autorisée.");
        }

        try {
            // L'hôte ne note que communication et propreté
            $notesDetaillees = $request->only(['proprete', 'communication']);
            
            Evaluation::creer(
                $user->id_utilisateur,               // Auteur (Hôte)
                $reservation->id_voyageur,           // Cible (Voyageur)
                $reservation->id_reservation, 
                $reservation->id_annonce, 
                'hote',                              // Type auteur
                $request->commentaire, 
                $notesDetaillees
            );

            return redirect()->route('hote.reservations.demandes')->with('success_dialog', 'Votre évaluation sur le locataire a été publiée avec succès.');
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
        ]);

        $evaluation = Evaluation::findOrFail($id);
        
        if ($evaluation->id_auteur !== Auth::id()) {
            abort(403);
        }

        try {
            $notesDetaillees = $request->only(['proprete', 'communication']);
            $evaluation->modifier($request->commentaire, $notesDetaillees);

            return redirect()->route('hote.evaluations.index', ['tab' => 'donnees'])->with('success_dialog', 'Votre évaluation a été modifiée avec succès.');
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
        
        if ($evaluation->id_auteur !== Auth::id()) {
            abort(403);
        }

        $evaluation->supprimer();

        return redirect()->route('hote.evaluations.index', ['tab' => 'donnees'])->with('success_dialog', 'L\'avis a été supprimé.');
    }

    /**
     * Signaler une évaluation reçue
     */
    public function signaler(Request $request, $id)
    {
        $request->validate([
            'motif' => 'required|string|max:500'
        ]);

        $evaluation = Evaluation::findOrFail($id);
        if ($evaluation->id_cible !== Auth::id()) {
            abort(403, "Vous ne pouvez signaler que les avis que vous avez reçus.");
        }

        $evaluation->signalerEvaluation($request->motif);
        $evaluation->genererTicket(Auth::id(), $request->motif);

        return redirect()->route('hote.evaluations.index', ['tab' => 'recues'])->with('success_dialog', 'L\'avis du voyageur a été signalé à notre équipe de modération.');
    }
}
