<?php

namespace App\Models\Annonces;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Enums\StatutAnnonce;
use App\Enums\ModeReservation;
use App\Models\Utilisateurs\User;
use App\Models\Reservations\Reservation;

class Annonce extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id_annonce';
    public $timestamps = false; // Usually true, but schema says `date_creation`? We can just let Eloquent handle custom if needed, schema has standard timestamps if we want to override.

    const CREATED_AT = 'date_creation';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_hote',
        'id_categorie',
        'id_politique',
        'titre',
        'description',
        'photo_url',
        'type_logement',
        'adresse',
        'capacite',
        'tarif_nuit',
        'mode_reservation',
        'statut',
        'equipements',
        'reglement_interieur',
        'date_creation'
    ];

    protected $casts = [
        'statut' => StatutAnnonce::class,
        'mode_reservation' => ModeReservation::class,
        'capacite' => 'integer',
        'tarif_nuit' => 'decimal:2',
    ];

    // Relations
    public function hote()
    {
        return $this->belongsTo(User::class, 'id_hote', 'id_utilisateur');
    }

    public function categorie()
    {
        return $this->belongsTo(CategorieGeographique::class, 'id_categorie', 'id_categorie');
    }

    public function politique()
    {
        return $this->belongsTo(PolitiqueAnnulation::class, 'id_politique', 'id_politique');
    }

    public function calendrier()
    {
        return $this->hasMany(Calendrier::class, 'id_annonce', 'id_annonce');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'id_annonce', 'id_annonce');
    }

    public function evaluations()
    {
        // Supposing PK_Evaluations Evaluation model exists
        return $this->hasMany(\App\Models\Evaluations\Evaluation::class, 'id_cible', 'id_annonce');
    }

    // UML Methods (Logic is generally in Services, placing delegate stubs here as per diagram)
    public function creerAnnonce() {}
    public function getDetailsAnnonce() { return $this; }
    public function verifierProprietaire($idHote) { return $this->id_hote === $idHote; }
    public function getStatutAnnonce() { return $this->statut; }
    public function modifierInformations() {}
    public function modifierPrix() {}
    public function modifierModeReservation() {}
    public function mettreAJourCategorie() {}
    public function mettreAJourPolitique() {}
    public function desactiverAnnonce() {
        $this->statut = StatutAnnonce::DESACTIVE;
        $this->save();
    }
    public function rechercherAnnonces() {}
    public function setStatusAnnonce($statut) {
        $this->statut = $statut;
        $this->save();
    }
    public function getAnnonces() {}
    public function getAnnoncesById() {}
    public function filtrerAnnonces($statut) {}
    public function getAnnoncesParHote() {}
    public function getAnnoncesDisponibles() {}
    public function suspendre() {
        $this->statut = StatutAnnonce::SUSPENDU;
        $this->save();
    }
    
    public function calculerNoteGlobale() 
    {
        // UML RG30 logic mock
        return 4.5;
    }
}
