<?php

namespace App\Http\Controllers\Annonces;

use App\Services\Annonces\AnnonceService;
use App\Http\Requests\Annonces\StoreAnnonceRequest;
use App\Http\Requests\Annonces\UpdateAnnonceRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AnnonceController
{
    use AuthorizesRequests;

    protected $annonceService;

    public function __construct(AnnonceService $annonceService)
    {
        $this->annonceService = $annonceService;
    }

    // Vue publique: liste des annonces + filtres
    public function index(Request $request)
    {
        $criteria = $request->only(['location', 'id_categorie', 'nb_voyageurs', 'checkin', 'checkout', 'type_logement']);
        $annonces = $this->annonceService->rechercherAnnonces($criteria);
        
        return view('welcome', compact('annonces'));
    }
    
    // Vue publique: details de l'annonce
    public function show($id)
    {
        $annonce = $this->annonceService->getDetailsAnnonce($id);
        $datesBloquees = $this->annonceService->getDatesOccupees($id);
        
        return view('annonces.show', compact('annonce', 'datesBloquees'));
    }

    // ----- VUES HOTE -----
    
    // Dashboard des annonces de l'hote
    public function mesAnnonces(Request $request)
    {
        $this->authorize('create', \App\Models\Annonces\Annonce::class);
        $annonces = \App\Models\Annonces\Annonce::where('id_hote', $request->user()->id_utilisateur)->get();
        return view('hote.annonces.index', compact('annonces'));
    }

    public function create()
    {
        $this->authorize('create', \App\Models\Annonces\Annonce::class);
        $categories = \App\Models\Annonces\CategorieGeographique::all();
        $politiques = \App\Models\Annonces\PolitiqueAnnulation::all();
        return view('hote.annonces.create', compact('categories', 'politiques'));
    }

    public function store(StoreAnnonceRequest $request)
    {
        $this->authorize('create', \App\Models\Annonces\Annonce::class);
        
        try {
            $data = $request->validated();
            
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('annonces', 'public');
                $data['photo_url'] = '/storage/' . $path;
            }

            $this->annonceService->creerAnnonce($data, $request->user());
            return redirect()->route('hote.annonces.index')->with('success_dialog', 'Annonce créée et mise en attente de vérification.');
        } catch (\Exception $e) {
            return back()->with('error_dialog', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $annonce = \App\Models\Annonces\Annonce::findOrFail($id);
        $this->authorize('update', $annonce);
        
        $categories = \App\Models\Annonces\CategorieGeographique::all();
        $politiques = \App\Models\Annonces\PolitiqueAnnulation::all();
        return view('hote.annonces.edit', compact('annonce', 'categories', 'politiques'));
    }

    public function update(UpdateAnnonceRequest $request, $id)
    {
        $annonce = \App\Models\Annonces\Annonce::findOrFail($id);
        $this->authorize('update', $annonce);

        try {
            $data = $request->validated();

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('annonces', 'public');
                $data['photo_url'] = '/storage/' . $path;
            }

            $this->annonceService->modifierInformations($id, $data, $request->user()->id_utilisateur);
            return redirect()->route('hote.annonces.index')->with('success_dialog', 'Annonce mise à jour avec succès.');
        } catch (\Exception $e) {
            return back()->with('error_dialog', $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        $annonce = \App\Models\Annonces\Annonce::findOrFail($id);
        $this->authorize('delete', $annonce);

        try {
            $this->annonceService->desactiverAnnonce($id, $request->user()->id_utilisateur);
            return redirect()->route('hote.annonces.index')->with('success_dialog', 'Annonce supprimée (Désactivée).');
        } catch (\Exception $e) {
            return back()->with('error_dialog', $e->getMessage());
        }
    }
}
