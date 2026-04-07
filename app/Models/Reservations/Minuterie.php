<?php

namespace App\Models\Reservations;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Minuterie extends Model
{
    use HasUuids;

    protected $table = 'minuteries';
    protected $primaryKey = 'id_minuterie';
    public $timestamps = false;

    protected $fillable = [
        'id_reservation',
        'type_minuterie',
        'date_echeance',
        'est_active',
    ];

    protected $casts = [
        'date_echeance' => 'datetime',
        'est_active' => 'boolean',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'id_reservation', 'id_reservation');
    }
}
