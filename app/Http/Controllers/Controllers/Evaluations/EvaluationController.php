<?php

namespace App\Http\Controllers\Controllers\Evaluations;

use App\Services\Evaluations\EvaluationService;
use App\Models\Reservations\Reservation;
use App\Models\Evaluations\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController
{
    protected EvaluationService $evaluationService;

    public function __construct(EvaluationService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }

    /**
     * GET /mes-evaluations
     * Liste des évaluations reçues par l'utilisateur connecté.
     */
    public function index()
    {
        $evaluations = $this->evaluationService->getEvaluations(Auth::id());

        return view('evaluations.index', compact('evaluations'));
    }

    /**
     * GET /reservations/{reservation}/evaluation/create
     * Affiche le formulaire d'évaluation pour une réservation terminée.
     */
    public function create(string $reservationId)
    {
        $reservation = Reservation::with(['annonce', 'voyageur', 'hote'])->findOrFail($reservationId);

        $verification = $this->evaluationService->peutEvaluer(Auth::id(), $reservation);

        if (!$verification['peut']) {
            return redirect()->back()->with('error', $verification['raison']);
        }

        $typeAuteur = $verification['type_auteur'];

        return view('evaluations.create', compact('reservation', 'typeAuteur'));
    }

    /**
     * POST /reservations/{reservation}/evaluation
     * Enregistre un nouvel avis.
     */
    public function store(Request $request, string $reservationId)
    {
        $request->validate([
            'proprete'             => 'required|numeric|min:1|max:5',
            'communication'        => 'required|numeric|min:1|max:5',
            'emplacement'          => 'required|numeric|min:1|max:5',
            'rapport_qualite_prix' => 'required|numeric|min:1|max:5',
            'exactitude'           => 'required|numeric|min:1|max:5',
            'commentaire'          => 'required|string|min:10|max:2000',
        ]);

        $reservation = Reservation::findOrFail($reservationId);

        try {
            $this->evaluationService->creer(
                $request->only(['proprete', 'communication', 'emplacement', 'rapport_qualite_prix', 'exactitude', 'commentaire']),
                Auth::id(),
                $reservation
            );

            return redirect()->route('mes-evaluations')->with('success', 'Votre avis a été enregistré avec succès !');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * GET /evaluations/{evaluation}/edit
     * Formulaire de modification d'un avis (RO12).
     */
    public function edit(string $evaluationId)
    {
        $evaluation = Evaluation::with(['noteDetaillee', 'reservation.annonce'])->findOrFail($evaluationId);

        if ($evaluation->id_auteur !== Auth::id()) {
            return redirect()->route('mes-evaluations')->with('error', "Vous n'êtes pas l'auteur de cet avis.");
        }

        // RO12 : vérifier le délai
        if ($evaluation->date_creation->diffInDays(now()) > 14) {
            return redirect()->route('mes-evaluations')->with('error', 'Le délai de modification de 14 jours est dépassé.');
        }

        return view('evaluations.edit', compact('evaluation'));
    }

    /**
     * PUT /evaluations/{evaluation}
     * Sauvegarde la modification d'un avis.
     */
    public function update(Request $request, string $evaluationId)
    {
        $request->validate([
            'proprete'             => 'required|numeric|min:1|max:5',
            'communication'        => 'required|numeric|min:1|max:5',
            'emplacement'          => 'required|numeric|min:1|max:5',
            'rapport_qualite_prix' => 'required|numeric|min:1|max:5',
            'exactitude'           => 'required|numeric|min:1|max:5',
            'commentaire'          => 'required|string|min:10|max:2000',
        ]);

        try {
            $this->evaluationService->modifier(
                $evaluationId,
                $request->only(['proprete', 'communication', 'emplacement', 'rapport_qualite_prix', 'exactitude', 'commentaire']),
                Auth::id()
            );

            return redirect()->route('mes-evaluations')->with('success', 'Votre avis a été mis à jour.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * DELETE /evaluations/{evaluation}
     * Suppression par l'auteur (RO12).
     */
    public function destroy(string $evaluationId)
    {
        try {
            $this->evaluationService->supprimer($evaluationId, Auth::id());
            return redirect()->route('mes-evaluations')->with('success', 'Votre avis a été supprimé.');
        } catch (\Exception $e) {
            return redirect()->route('mes-evaluations')->with('error', $e->getMessage());
        }
    }

    /**
     * POST /evaluations/{evaluation}/signaler
     * Signaler un avis (RO10).
     */
    public function signaler(Request $request, string $evaluationId)
    {
        $request->validate([
            'motif_signalement' => 'required|string|min:5|max:500',
        ]);

        try {
            $this->evaluationService->signalerEvaluation(
                $evaluationId,
                $request->motif_signalement,
                Auth::id()
            );

            return redirect()->back()->with('success', 'L\'avis a été signalé. Un ticket de litige a été créé.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
