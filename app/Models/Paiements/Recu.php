<?php

namespace App\Models\Paiements;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Recu extends Model
{
    use HasFactory;

    protected $table = 'recus';
    protected $primaryKey = 'id_recu';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_recu',
        'id_paiement',
        'numero_facture',
        'chemin_pdf',
        'date_generation'
    ];

    protected $casts = [
        'date_generation' => 'datetime'
    ];

    public function paiement()
    {
        return $this->belongsTo(Paiement::class, 'id_paiement', 'id_paiement');
    }
}
