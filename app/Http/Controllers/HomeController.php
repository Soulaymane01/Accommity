<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController
{
    /**
     * Display the landing page with annonces.
     */
    public function index()
    {
        try {
            // Fetch 10 annonces joined with categories for the 'ville' field
            $annonces = DB::table('annonces')
                ->leftJoin('categorie_geographiques', 'annonces.id_categorie', '=', 'categorie_geographiques.id_categorie')
                ->select('annonces.*', 'categorie_geographiques.ville')
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            // Fallback empty collection if the table does not exist or errors out
            $annonces = collect([]);
        }

        return view('welcome', compact('annonces'));
    }
}
