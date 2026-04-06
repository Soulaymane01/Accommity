<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. UTILISATEUR
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->uuid('id_utilisateur')->primary();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('mot_de_passe');
            $table->string('telephone');
            $table->boolean('est_hote');
            $table->boolean('est_voyageur');
            $table->timestamp('date_creation');
        });

        // 2. PROFIL
        Schema::create('profils', function (Blueprint $table) {
            $table->uuid('id_profil')->primary();
            $table->uuid('id_utilisateur');
            $table->string('photo_url')->nullable();
            $table->text('description')->nullable();
            $table->float('note_moyenne')->default(0);

            $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
        });

        // 3. SESSION
        Schema::create('sessions', function (Blueprint $table) {
            $table->uuid('id_session')->primary();
            $table->uuid('id_utilisateur');
            $table->string('token');
            $table->timestamp('date_expiration');

            $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
        });

        // 4. VERIFICATION_IDENTITE
        Schema::create('verification_identites', function (Blueprint $table) {
            $table->uuid('id_verification')->primary();
            $table->uuid('id_utilisateur');
            $table->string('type_piece');
            $table->string('statut');
            $table->timestamp('date_soumission');

            $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
        });

        // 5. ADMINISTRATEUR
        Schema::create('administrateurs', function (Blueprint $table) {
            $table->uuid('id_admin')->primary();
            $table->uuid('id_utilisateur');
            $table->string('role');
            $table->string('email')->unique();
            $table->string('mot_de_passe');
            $table->dateTime('derniere_connexion');

            $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
        });

        // 6. CATEGORIE_GEOGRAPHIQUE
        Schema::create('categorie_geographiques', function (Blueprint $table) {
            $table->uuid('id_categorie')->primary();
            $table->string('ville');
            $table->string('region');
            $table->string('pays');
        });

        // 7. POLITIQUE_ANNULATION
        Schema::create('politique_annulations', function (Blueprint $table) {
            $table->uuid('id_politique')->primary();
            $table->enum('type_politique', ['flexible', 'modérée', 'stricte']);
            $table->smallInteger('delai_remb_total');
            $table->smallInteger('delai_remb_partiel');
            $table->decimal('taux_remb_partiel', 5, 2);
            $table->text('description');
        });

        // 8. ANNONCE
        Schema::create('annonces', function (Blueprint $table) {
            $table->uuid('id_annonce')->primary();
            $table->uuid('id_hote');
            $table->uuid('id_categorie');
            $table->uuid('id_politique')->nullable();
            $table->string('titre');
            $table->text('description');
            $table->string('type_logement');
            $table->string('adresse');
            $table->smallInteger('capacite');
            $table->decimal('tarif_nuit', 10, 2);
            $table->enum('mode_reservation', ['réservation instantanée', 'demande de réservation']);
            $table->enum('statut', ['En cours de vérification', 'Publié', 'Suspendu', 'supprimer', 'rejeté']);
            $table->text('equipements');
            $table->text('reglement_interieur')->nullable();
            $table->timestamp('date_creation');

            $table->foreign('id_hote')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('id_categorie')->references('id_categorie')->on('categorie_geographiques')->onDelete('cascade');
            $table->foreign('id_politique')->references('id_politique')->on('politique_annulations')->onDelete('set null');
        });

        // 9. CALENDRIER
        Schema::create('calendriers', function (Blueprint $table) {
            $table->uuid('id_calendrier')->primary();
            $table->uuid('id_annonce');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->boolean('est_disponible');

            $table->foreign('id_annonce')->references('id_annonce')->on('annonces')->onDelete('cascade');
        });

        // 10. RESERVATION
        Schema::create('reservations', function (Blueprint $table) {
            $table->uuid('id_reservation')->primary();
            $table->uuid('id_annonce');
            $table->uuid('id_voyageur');
            $table->uuid('id_hote');
            $table->uuid('id_politique')->nullable();
            $table->date('date_arrivee');
            $table->date('date_depart');
            $table->smallInteger('nb_voyageurs');
            $table->enum('statut', ['En attente', 'Confirmée', 'En cours', 'Terminée', 'Annulée', 'Refusée', 'Expirée']);
            $table->enum('mode_reservation', ['réservation instantanée', 'demande de réservation']);
            $table->decimal('montant_total', 10, 2);
            $table->decimal('frais_service', 10, 2);
            $table->text('message_optionnel')->nullable();
            $table->enum('acteur_annulation', ['voyageur', 'hote', 'systeme'])->nullable();
            $table->timestamp('date_creation');
            $table->timestamp('date_modification')->nullable();

            $table->foreign('id_annonce')->references('id_annonce')->on('annonces')->onDelete('cascade');
            $table->foreign('id_voyageur')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('id_hote')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('id_politique')->references('id_politique')->on('politique_annulations')->onDelete('set null');
        });

        // 11. MINUTERIE
        Schema::create('minuteries', function (Blueprint $table) {
            $table->uuid('id_minuterie')->primary();
            $table->uuid('id_reservation')->nullable();
            $table->enum('type_minuterie', ['expiration_demande', 'autre']);
            $table->timestamp('date_echeance');
            $table->boolean('est_active');

            $table->foreign('id_reservation')->references('id_reservation')->on('reservations')->onDelete('cascade');
        });

        // 12. RECAPITULATIF_RESERVATION
        Schema::create('recapitulatif_reservations', function (Blueprint $table) {
            $table->uuid('id_recapitulatif')->primary();
            $table->uuid('id_reservation');
            $table->text('details_sejour');
            $table->decimal('montant_total', 10, 2);
            $table->decimal('frais_service', 10, 2);
            $table->decimal('montant_hote', 10, 2);
            $table->text('coordonnees_voyageur');
            $table->text('coordonnees_hote');
            $table->timestamp('date_generation');

            $table->foreign('id_reservation')->references('id_reservation')->on('reservations')->onDelete('cascade');
        });

        // 13. PAIEMENT
        Schema::create('paiements', function (Blueprint $table) {
            $table->uuid('id_paiement')->primary();
            $table->uuid('id_reservation');
            $table->uuid('id_voyageur');
            $table->decimal('montant', 10, 2);
            $table->timestamp('date_transaction');
            $table->enum('methode_paiement', ['carte_bancaire', 'paypal', 'autre']);
            $table->enum('statut', ['en_attente', 'reussi', 'echoue', 'rembourse']);

            $table->foreign('id_reservation')->references('id_reservation')->on('reservations')->onDelete('cascade');
            $table->foreign('id_voyageur')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
        });

        // 14. VERSEMENT
        Schema::create('versements', function (Blueprint $table) {
            $table->uuid('id_versement')->primary();
            $table->uuid('id_reservation');
            $table->uuid('id_hote');
            $table->decimal('montant', 10, 2);
            $table->timestamp('date_versement');
            $table->string('reference_bancaire');
            $table->enum('statut', ['en_attente', 'traite', 'echoue']);

            $table->foreign('id_reservation')->references('id_reservation')->on('reservations')->onDelete('cascade');
            $table->foreign('id_hote')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
        });

        // 15. REMBOURSEMENT
        Schema::create('remboursements', function (Blueprint $table) {
            $table->uuid('id_remboursement')->primary();
            $table->uuid('id_reservation');
            $table->uuid('id_voyageur');
            $table->decimal('montant', 10, 2);
            $table->timestamp('date_remboursement');
            $table->enum('motif', ['annulation_voyageur', 'annulation_hote', 'expiration_demande']);

            $table->foreign('id_reservation')->references('id_reservation')->on('reservations')->onDelete('cascade');
            $table->foreign('id_voyageur')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
        });

        // 16. RECU
        Schema::create('recus', function (Blueprint $table) {
            $table->uuid('id_recu')->primary();
            $table->uuid('id_paiement');
            $table->string('numero_facture');
            $table->string('chemin_pdf');
            $table->timestamp('date_generation');

            $table->foreign('id_paiement')->references('id_paiement')->on('paiements')->onDelete('cascade');
        });

        // 17. EVALUATION
        Schema::create('evaluations', function (Blueprint $table) {
            $table->uuid('id_evaluation')->primary();
            $table->uuid('id_reservation');
            $table->uuid('id_auteur');
            $table->uuid('id_cible');
            $table->uuid('id_annonce')->nullable();
            $table->enum('type_auteur', ['voyageur', 'hote']);
            $table->decimal('note', 3, 2);
            $table->text('commentaire');
            $table->boolean('est_signale')->default(false);
            $table->string('motif_signalement')->nullable();
            $table->timestamp('date_creation');
            $table->timestamp('date_modification')->nullable();

            $table->foreign('id_reservation')->references('id_reservation')->on('reservations')->onDelete('cascade');
            $table->foreign('id_auteur')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('id_cible')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('id_annonce')->references('id_annonce')->on('annonces')->onDelete('set null');
        });

        // 18. NOTE_DETAILLEE
        Schema::create('note_detaillees', function (Blueprint $table) {
            $table->uuid('id_note')->primary();
            $table->uuid('id_evaluation');
            $table->decimal('proprete', 3, 2);
            $table->decimal('communication', 3, 2);
            $table->decimal('emplacement', 3, 2);
            $table->decimal('rapport_qualite_prix', 3, 2);
            $table->decimal('exactitude', 3, 2);

            $table->foreign('id_evaluation')->references('id_evaluation')->on('evaluations')->onDelete('cascade');
        });

        // 19. TICKET_LITIGE
        Schema::create('ticket_litiges', function (Blueprint $table) {
            $table->uuid('id_ticket')->primary();
            $table->uuid('id_evaluation');
            $table->uuid('id_declarant');
            $table->uuid('id_admin');
            $table->string('motif');
            $table->string('statut');
            $table->timestamp('date_creation');
            $table->timestamp('date_cloture')->nullable();

            $table->foreign('id_evaluation')->references('id_evaluation')->on('evaluations')->onDelete('cascade');
            $table->foreign('id_declarant')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('id_admin')->references('id_admin')->on('administrateurs')->onDelete('cascade');
        });

        // 20. NOTIFICATION
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id_notification')->primary();
            $table->uuid('id_utilisateur');
            $table->string('titre');
            $table->text('contenu');
            $table->enum('type_alerte', ['email', 'sms', 'push']);
            $table->boolean('est_lue')->default(false);
            $table->timestamp('date_creation');

            $table->foreign('id_utilisateur')->references('id_utilisateur')->on('utilisateurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('ticket_litiges');
        Schema::dropIfExists('note_detaillees');
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('recus');
        Schema::dropIfExists('remboursements');
        Schema::dropIfExists('versements');
        Schema::dropIfExists('paiements');
        Schema::dropIfExists('recapitulatif_reservations');
        Schema::dropIfExists('minuteries');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('calendriers');
        Schema::dropIfExists('annonces');
        Schema::dropIfExists('politique_annulations');
        Schema::dropIfExists('categorie_geographiques');
        Schema::dropIfExists('administrateurs');
        Schema::dropIfExists('verification_identites');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('profils');
        Schema::dropIfExists('utilisateurs');
    }
};
