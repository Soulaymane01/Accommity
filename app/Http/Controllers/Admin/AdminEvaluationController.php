<?php

namespace App\Http\Controllers\Admin;

use App\Services\Evaluations\EvaluationService;
use Illuminate\Http\Request;

class AdminEvaluationController
{
    protected EvaluationService $evaluationService;

    public function __construct(EvaluationService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }

    /**
     * GET /admin/avis-signales
     * Liste des avis signalés avec détails pour modération.
     */
    public function indexSignales()
    {
        $avisSignales = $this->evaluationService->getEvaluationsSignalees();

        return view('admin.avis_signales.index', compact('avisSignales'));
    }

    /**
     * DELETE /admin/evaluations/{id}
     * RG31 : suppression d'un avis inapproprié par l'admin.
     */
    public function supprimerAvis(string $evaluationId)
    {
        try {
            $this->evaluationService->supprimerEvaluation($evaluationId);
            return redirect()->route('admin.avis_signales.index')->with('success', 'L\'avis a été supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('admin.avis_signales.index')->with('error', $e->getMessage());
        }
    }

    /**
     * POST /admin/evaluations/{id}/conserver
     * Admin : conserver un avis signalé (enlever le flag).
     */
    public function conserverAvis(string $evaluationId)
    {
        try {
            $this->evaluationService->conserverEvaluation($evaluationId);
            return redirect()->route('admin.avis_signales.index')->with('success', 'L\'avis a été conservé et le signalement retiré.');
        } catch (\Exception $e) {
            return redirect()->route('admin.avis_signales.index')->with('error', $e->getMessage());
        }
    }
}
