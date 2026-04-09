<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\Evaluations\Evaluation;
use App\Models\Utilisateurs\User;
use App\Enums\TicketLitigeStatut;

class TicketLitige extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ticket_litiges';
    protected $primaryKey = 'id_ticket';
    public $timestamps = false;
    
    const CREATED_AT = 'date_creation';

    protected $fillable = [
        'id_evaluation',
        'id_declarant',
        'id_admin',
        'motif',
        'statut',
        'date_creation',
        'date_cloture'
    ];

    protected $casts = [
        'statut' => TicketLitigeStatut::class,
        'date_creation' => 'datetime',
        'date_cloture' => 'datetime',
    ];

    // Relations
    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'id_evaluation', 'id_evaluation');
    }

    public function declarant()
    {
        return $this->belongsTo(User::class, 'id_declarant', 'id_utilisateur');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id_utilisateur'); // Or Administrateur model
    }

    // UML Methods
    public static function getLitiges()
    {
        return self::with(['evaluation.reservation', 'declarant'])->latest('date_creation')->paginate(10);
    }

    public static function getLitigeById($id)
    {
        return self::with(['evaluation.reservation.hote', 'evaluation.reservation.voyageur', 'declarant'])->where('id_ticket', $id)->first();
    }

    public static function filtrerLitiges($statut)
    {
        if ($statut && $statut !== 'tous') {
            $enumVal = TicketLitigeStatut::tryFrom($statut);
            return self::with(['evaluation.reservation', 'declarant'])
                ->where('statut', $enumVal ?? $statut)
                ->latest('date_creation')
                ->paginate(10);
        }
        return self::getLitiges();
    }

    public function cloturerLitige()
    {
        $this->statut = TicketLitigeStatut::CLOTURE;
        $this->date_cloture = now();
        $this->save();
    }
}
