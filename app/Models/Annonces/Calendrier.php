<?php

namespace App\Models\Annonces;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Enums\TypeBlockageCalendrier;

class Calendrier extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id_calendrier';
    public $timestamps = false;

    protected $fillable = [
        'id_annonce',
        'date_debut',
        'date_fin',
        'est_disponible',
        'type_blockage',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'est_disponible' => 'boolean',
        'type_blockage' => TypeBlockageCalendrier::class,
    ];

    // Relations
    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'id_annonce', 'id_annonce');
    }

    // UML Methods (Usually mapped to a Service)
    public function initialiserCalendrier() {}
    public function getCalendrier() { return $this; }
    public function getDisponibilites() {}
    public function verifierDisponibilite($dateArrivee, $dateDepart) {}
    
    public function bloquerDatesManuel($dateDebut, $dateFin) 
    {
        $this->update([
            'est_disponible' => false,
            'type_blockage' => TypeBlockageCalendrier::BLOQUE_MANUEL,
        ]);
    }
    
    public function debloquerDates($dateDebut, $dateFin) 
    {
        $this->update([
            'est_disponible' => true,
            'type_blockage' => TypeBlockageCalendrier::DISPONIBLE,
        ]);
    }
    
    public function supprimerCalendrier() 
    {
        $this->delete();
    }
}
