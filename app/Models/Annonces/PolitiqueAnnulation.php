<?php

namespace App\Models\Annonces;

use App\Enums\TypePolitiqueAnnulation;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PolitiqueAnnulation extends Model
{
    use HasUuids;

    protected $table = 'politique_annulations';
    protected $primaryKey = 'id_politique';
    public $timestamps = false;

    protected $fillable = [
        'type_politique',
        'delai_remb_total',
        'delai_remb_partiel',
        'taux_remb_partiel',
        'description',
    ];

    protected $casts = [
        'type_politique' => TypePolitiqueAnnulation::class,
    ];

    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'id_politique', 'id_politique');
    }

    // UML Methods
    public function definirPolitiqueAnnulation($type) {}
    public function modifierPolitiqueAnnulation($type) {}
    public function calculerRemboursement($montant, $dateAnnulation) : float { return 0.0; }
    public function remboursementPartiel($idReservation) {}
    public function remboursementIntegral($idReservation) {}
}
