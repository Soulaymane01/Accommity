<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\AnnonceRepositoryInterface;
use App\Models\Annonces\Annonce;
use App\Enums\StatutAnnonce;
use Illuminate\Database\Eloquent\Collection;

class AnnonceRepository implements AnnonceRepositoryInterface
{
    public function findById($id): ?Annonce
    {
        return Annonce::with(['hote', 'categorie', 'politique', 'calendrier'])->find($id);
    }

    public function create(array $data): Annonce
    {
        return Annonce::create($data);
    }

    public function update($id, array $data): bool
    {
        $annonce = Annonce::find($id);
        if ($annonce) {
            return $annonce->update($data);
        }
        return false;
    }

    public function delete($id): bool
    {
        $annonce = Annonce::find($id);
        if ($annonce) {
            // Logique de desactivation plutot que suppression physique (RG UML)
            $annonce->statut = StatutAnnonce::DESACTIVE;
            return $annonce->save();
        }
        return false;
    }

    public function getAnnoncesParHote($idHote): Collection
    {
        return Annonce::where('id_hote', $idHote)->get();
    }

    public function rechercherAnnoncesDisponibles(array $criteria): Collection
    {
        $query = Annonce::where('statut', StatutAnnonce::PUBLIE);

        // Recherche par ID catégorie exact
        if (!empty($criteria['id_categorie'])) {
            $query->where('id_categorie', $criteria['id_categorie']);
        }
        
        // Recherche par type de logement
        if (!empty($criteria['type_logement'])) {
            $query->where('type_logement', $criteria['type_logement']);
        }

        // Recherche textuelle par localisation (ville, pays, région)
        if (!empty($criteria['location'])) {
            $loc = $criteria['location'];
            $query->whereHas('categorie', function($q) use ($loc) {
                $q->where('ville', 'LIKE', "%{$loc}%")
                  ->orWhere('pays', 'LIKE', "%{$loc}%")
                  ->orWhere('region', 'LIKE', "%{$loc}%");
            });
        }
        
        if (!empty($criteria['nb_voyageurs'])) {
            $query->where('capacite', '>=', $criteria['nb_voyageurs']);
        }
        
        // Filtrage par dates via la table calendriers
        if (!empty($criteria['checkin']) && !empty($criteria['checkout'])) {
            $checkin = $criteria['checkin'];
            $checkout = $criteria['checkout'];

            // On cherche les annonces qui ont des calendriers marquant "Disponible" sur TOUT l'intervalle
            // OU plus simplement on exclut celles qui ont une réservation confirmée sur ces dates
            $query->whereDoesntHave('reservations', function($q) use ($checkin, $checkout) {
                $q->whereIn('statut', ['Confirmée', 'En cours'])
                  ->where(function($sub) use ($checkin, $checkout) {
                      $sub->where(function($sq) use ($checkin, $checkout) {
                          $sq->where('date_arrivee', '>=', $checkin)
                             ->where('date_arrivee', '<', $checkout);
                      })->orWhere(function($sq) use ($checkin, $checkout) {
                          $sq->where('date_depart', '>', $checkin)
                             ->where('date_depart', '<=', $checkout);
                      })->orWhere(function($sq) use ($checkin, $checkout) {
                          $sq->where('date_arrivee', '<=', $checkin)
                             ->where('date_depart', '>=', $checkout);
                      });
                  });
            });
        }
        
        return $query->with(['categorie', 'hote'])->get();
    }

    public function getAnnoncesDisponibles(): Collection
    {
        return Annonce::where('statut', StatutAnnonce::PUBLIE)->get();
    }
}
