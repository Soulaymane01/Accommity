<?php

namespace App\Models\Administration;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Administrateur extends Authenticatable
{
    use Notifiable;

    protected $table = 'administrateurs';
    protected $primaryKey = 'id_admin';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // No created_at/updated_at by default in our schema

    protected $fillable = [
        'id_admin',
        'id_utilisateur',
        'email',
        'mot_de_passe',
        'derniere_connexion',
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    /**
     * Get the password for the user. Overrides Authenticatable.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    /**
     * Define the relationship to Utilisateur if the admin has a standard account.
     */
    public function utilisateur()
    {
        return $this->belongsTo(\App\Models\Utilisateurs\User::class, 'id_utilisateur', 'id_utilisateur');
    }
}
