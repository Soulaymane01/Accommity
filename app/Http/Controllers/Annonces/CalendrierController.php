<?php

namespace App\Http\Controllers\Annonces;

use App\Services\Annonces\CalendrierService;
use Illuminate\Http\Request;
use App\Models\Annonces\Annonce;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CalendrierController
{
    use AuthorizesRequests;

    protected $calendrierService;

    public function __construct(CalendrierService $calendrierService)
    {
        $this->calendrierService = $calendrierService;
    }

    public function index(Request $request)
    {
        // Shows all host calendars
        $annonces = Annonce::where('id_hote', $request->user()->id_utilisateur)->with('calendrier')->get();
        return view('hote.calendrier.index', compact('annonces'));
    }

    public function bloquer(Request $request, $idAnnonce)
    {
        $annonce = Annonce::findOrFail($idAnnonce);
        $this->authorize('update', $annonce); // proprietaire

        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut'
        ]);

        try {
            $this->calendrierService->bloquerDatesManuel($idAnnonce, $request->date_debut, $request->date_fin);
            return back()->with('success_dialog', 'Dates bloquées avec succès.');
        } catch (\Exception $e) {
            return back()->with('error_dialog', $e->getMessage());
        }
    }

    public function debloquer(Request $request, $idAnnonce)
    {
        $annonce = Annonce::findOrFail($idAnnonce);
        $this->authorize('update', $annonce); // proprietaire

        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut'
        ]);

        try {
            $this->calendrierService->debloquerDates($idAnnonce, $request->date_debut, $request->date_fin);
            return back()->with('success_dialog', 'Dates débloquées avec succès.');
        } catch (\Exception $e) {
            return back()->with('error_dialog', $e->getMessage());
        }
    }
}
