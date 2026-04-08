<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController
{
    /**
     * Display the landing page with annonces.
     */
    protected $annonceService;

    public function __construct(\App\Services\Annonces\AnnonceService $annonceService)
    {
        $this->annonceService = $annonceService;
    }

    /**
     * Display the landing page with annonces.
     */
    public function index()
    {
        try {
            // Use the same logic as listing page but with no filters
            $annonces = $this->annonceService->rechercherAnnonces([]);
        } catch (\Exception $e) {
            // Fallback empty collection if the table does not exist or errors out
            $annonces = collect([]);
        }

        return view('welcome', compact('annonces'));
    }
}
