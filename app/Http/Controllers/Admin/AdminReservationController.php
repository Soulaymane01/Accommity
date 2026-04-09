<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Reservations\Reservation;

class AdminReservationController
{
    /**
     * Affiche la liste des réservations avec filtrage
     */
    public function index(Request $request)
    {
        $critere = $request->input('critere', 'tous');
        $searchQuery = $request->input('search');
        $selectedId = $request->input('selected');
        
        $reservations = Reservation::filtrerReservations($critere);
        
        // Manual search filtering if a name is supplied
        if ($searchQuery) {
            $reservations = Reservation::with(['annonce', 'voyageur', 'hote'])->where(function($query) use ($searchQuery) {
                $query->whereHas('voyageur', function($q) use ($searchQuery) {
                          $q->where('nom', 'ilike', '%' . $searchQuery . '%')
                            ->orWhere('prenom', 'ilike', '%' . $searchQuery . '%');
                      })
                      ->orWhereHas('hote', function($q) use ($searchQuery) {
                          $q->where('nom', 'ilike', '%' . $searchQuery . '%')
                            ->orWhere('prenom', 'ilike', '%' . $searchQuery . '%');
                      })
                      ->orWhereHas('annonce', function($q) use ($searchQuery) {
                          $q->where('titre', 'ilike', '%' . $searchQuery . '%');
                      });
            })->latest('date_creation')->paginate(10);
        }
        
        $selectedReservation = null;
        if ($selectedId) {
            $selectedReservation = Reservation::with(['annonce', 'voyageur', 'hote'])->where('id_reservation', $selectedId)->first();
        }

        return view('admin.reservations.index', compact('reservations', 'critere', 'searchQuery', 'selectedReservation'));
    }
}
