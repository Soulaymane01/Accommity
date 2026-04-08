<?php

namespace App\Models\Annonces;

use App\Models\Utilisateurs\User;
use App\Models\Evaluations\Evaluation;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasUuids;

    protected $table = 'annonces';
    protected $primaryKey = 'id_annonce';
    public $timestamps = false;

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
        'date_creation',
    ];

    protected $casts = [
        'tarif_nuit'    => 'decimal:2',
        'capacite'      => 'integer',
        'date_creation' => 'datetime',
    ];

    // Relations

    public function hote()
    {
        return $this->belongsTo(User::class, 'id_hote', 'id_utilisateur');
    }

    public function categorie()
    {
        return $this->belongsTo(\App\Models\Annonces\CategorieGeographique::class, 'id_categorie', 'id_categorie');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'id_annonce', 'id_annonce');
    }
}
