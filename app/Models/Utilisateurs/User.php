<?php

namespace App\Models\Utilisateurs;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'utilisateurs';
    protected $primaryKey = 'id_utilisateur';
    public $incrementing = false;
    protected $keyType = 'string';
    
    // We disable default timestamps because Laravel expects created_at and updated_at
    // We will manually manage `date_creation`
    public $timestamps = false;

    protected $fillable = [
        'id_utilisateur',
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'telephone',
        'est_hote',
        'est_voyageur',
        'date_creation',
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    /**
     * Override the password field for authentication
     */
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    /**
     * Relationship to Profile
     */
    public function profil()
    {
        return $this->hasOne(Profil::class, 'id_utilisateur', 'id_utilisateur');
    }

    // --- METHODES UML: DIAGRAMME DE CLASSES --- //

    /**
     * Vérifier si un email est unique.
     * @param string $email
     * @return bool
     */
    public static function verifierEmailUnique(string $email): bool
    {
        return self::where('email', $email)->doesntExist();
    }

    /**
     * Créer un compte utilisateur.
     * @param array $data 
     * @return User
     */
    public static function creerCompte(array $data): self
    {
        return self::create($data);
    }

    /**
     * Récupère le rôle de l'utilisateur (hote ou voyageur)
     * @return string
     */
    public function getRoleUtilisateur(): string
    {
        if ($this->est_hote) {
            return 'hote';
        }
        if ($this->est_voyageur) {
            return 'voyageur';
        }
        return 'inconnu';
    }

    /**
     * Obtient le statut de vérification si hôte (simulate for now as relation)
     * @return \App\Enums\VerificationStatut|null
     */
    public function getStatutVerification(): ?\App\Enums\VerificationStatut
    {
        $verification = VerificationIdentite::where('id_utilisateur', $this->id_utilisateur)->latest('date_soumission')->first();
        return $verification ? $verification->statut : null;
    }

    // Note: listerEvaluations(), getUtilisateurById(), updateUser(), deleteUser() 
    // are naturally handled by Eloquent but can be wrapped if strictly needed.
}
