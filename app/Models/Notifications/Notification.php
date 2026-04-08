<?php

namespace App\Models\Notifications;

use App\Enums\TypeAlerte;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Utilisateurs\User;

class Notification extends Model
{
    use HasUuids;

    protected $table      = 'notifications';
    protected $primaryKey = 'id_notification';
    public    $timestamps = false;

    protected $fillable = [
        'id_utilisateur',
        'titre',
        'contenu',
        'type_alerte',
        'est_lue',
        'date_creation',
    ];

    /**
     * Cast the type_alerte column to the TypeAlerte backed enum.
     */
    protected $casts = [
        'type_alerte'    => TypeAlerte::class,
        'est_lue'        => 'boolean',
        'date_creation'  => 'datetime',
    ];

    // ─── Relationships ──────────────────────────────────────────────────────

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_utilisateur', 'id_utilisateur');
    }
}
