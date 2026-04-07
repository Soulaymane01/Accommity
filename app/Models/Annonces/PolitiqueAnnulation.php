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

    // Method expected by the Class diagram
    public function calculerMontantRemboursement($dateAnnulation, $dateArrivee): float
    {
        // Stub: Implement complex logic for penalty calculation
        return 0.0;
    }
}
