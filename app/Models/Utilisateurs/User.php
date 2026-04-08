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

    // --- METHODES UML: CYCLE INGENIEUR --- //

    public static function getUtilisateurs()
    {
        return self::latest('date_creation')->paginate(10);
    }

    public static function rechercherUtilisateur($critere, $searchQuery = null)
    {
        $query = self::query();

        if ($critere === 'hote') {
            $query->where('est_hote', true);
        } elseif ($critere === 'voyageur') {
            $query->where('est_voyageur', true);
        } elseif ($critere === 'en_attente') {
            $query->whereHas('verificationIdentite', function($q) {
                $q->where('statut', \App\Enums\VerificationStatut::EN_COURS);
            });
        }

        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('nom', 'ilike', '%' . $searchQuery . '%')
                  ->orWhere('prenom', 'ilike', '%' . $searchQuery . '%')
                  ->orWhere('email', 'ilike', '%' . $searchQuery . '%');
            });
        }

        return $query->latest('date_creation')->paginate(10);
    }

    public static function getUtilisateurById($idUtilisateur)
    {
        return self::with('profil', 'verificationIdentite')->where('id_utilisateur', $idUtilisateur)->first();
    }

    public function updateUser(array $donnees): void
    {
        $this->update($donnees);
    }

    public function deleteUser(): void
    {
        $this->delete();
    }

    public function mettreAJourStatut(string $statut): void
    {
        // Placeholder for future extensibility if User status needed
    }

    public function verificationIdentite()
    {
        return $this->hasOne(\App\Models\Utilisateurs\VerificationIdentite::class, 'id_utilisateur', 'id_utilisateur')->latest('date_soumission');
    }
}
