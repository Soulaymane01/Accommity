<?php

namespace App\Services\Annonces;

use App\Repositories\Interfaces\AnnonceRepositoryInterface;
use App\Models\Annonces\Annonce;
use App\Enums\StatutAnnonce;
use App\Models\Utilisateurs\User;

class AnnonceService
{
    protected $repository;
    protected $calendrierService;

    public function __construct(AnnonceRepositoryInterface $repository, CalendrierService $calendrierService)
    {
        $this->repository = $repository;
        $this->calendrierService = $calendrierService;
    }

    // DS1: Rechercher un hébergement
    public function rechercherAnnonces(array $criteria)
    {
        return $this->repository->rechercherAnnoncesDisponibles($criteria);
    }

    // DS2: Consulter une annonce
    public function getDetailsAnnonce($idAnnonce)
    {
        $annonce = $this->repository->findById($idAnnonce);
        if (!$annonce || $annonce->statut !== StatutAnnonce::PUBLIE) {
            throw new \Exception("Annonce non disponible ou inexistante.");
        }
        return $annonce;
    }

    // DS3: Créer une annonce
    public function creerAnnonce(array $data, User $hote)
    {
        if (!$hote->est_hote) {
             throw new \Exception("Vérifiez votre identité ou rôle hôte."); 
        }

        // RG07: Verifier champs obligatoires (en général fait dans le Request public mais RG applicative)
        if (empty($data['titre']) || empty($data['description']) || empty($data['adresse'])) {
             throw new \Exception("Champs obligatoires manquants.");
        }

        $data['id_hote'] = $hote->id_utilisateur;
        $data['statut'] = StatutAnnonce::PUBLIE->value; // RO02: Initialement EN_VERIFICATION (temporairement PUBLIE pour dev)
        
        $data['date_creation'] = now();
        $data['photo_url'] = collect($data)->get('photo_url', '');
        $data['equipements'] = collect($data)->get('equipements', 'Standard');

        $annonce = $this->repository->create($data);

        // RO03: Calendrier initialisé automatiquement
        $this->calendrierService->initialiserCalendrier($annonce->id_annonce);

        return $annonce;
    }

    // DS5: Modifier annonce
    public function modifierInformations($idAnnonce, array $data, $idHote)
    {
        $annonce = $this->repository->findById($idAnnonce);
        if (!$annonce || !$annonce->verifierProprietaire($idHote)) {
            throw new \Exception("Non autorisé.");
        }

        if (!in_array($annonce->statut, [StatutAnnonce::PUBLIE, StatutAnnonce::SUSPENDU])) {
            throw new \Exception("Modification impossible pour ce statut.");
        }

        return $this->repository->update($idAnnonce, $data);
    }

    // DS6: Supprimer une annonce
    public function desactiverAnnonce($idAnnonce, $idHote)
    {
        $annonce = $this->repository->findById($idAnnonce);
        
        if (!$annonce || !$annonce->verifierProprietaire($idHote)) {
            throw new \Exception("Non autorisé.");
        }

        // Verifier réservations actives
        $hasActiveReservations = $annonce->reservations()
            ->whereIn('statut', ['En cours', 'Confirmée'])
            ->exists();

        if ($hasActiveReservations) {
            throw new \Exception("Suppression impossible, des réservations actives existent.");
        }

        // Suppression en cascade du calendrier
        $this->calendrierService->supprimerCalendrier($idAnnonce);

        // Désactivation (Suppression logique)
        return $this->repository->delete($idAnnonce);
    }

    public function getDatesOccupees($idAnnonce)
    {
        $annonce = $this->repository->findById($idAnnonce);
        
        $occupees = [];

        // 1. Réservations confirmées
        foreach ($annonce->reservations()->whereIn('statut', [\App\Enums\StatutReservation::CONFIRMEE, \App\Enums\StatutReservation::EN_COURS])->get() as $res) {
            $curr = \Carbon\Carbon::parse($res->date_arrivee);
            $end = \Carbon\Carbon::parse($res->date_depart);
            while ($curr->lt($end)) {
                $occupees[] = $curr->format('Y-m-d');
                $curr->addDay();
            }
        }

        // 2. Blocages manuels
        foreach ($annonce->calendrier()->where('est_disponible', false)->get() as $cal) {
            $curr = \Carbon\Carbon::parse($cal->date_debut);
            $end = \Carbon\Carbon::parse($cal->date_fin);
            while ($curr->lte($end)) {
                $occupees[] = $curr->format('Y-m-d');
                $curr->addDay();
            }
        }

        return array_unique($occupees);
    }
}
