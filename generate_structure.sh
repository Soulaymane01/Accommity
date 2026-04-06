#!/bin/bash

set -e

BASE="accommity"

echo "Generating folder structure for $BASE..."

# ── app/Console/Commands
mkdir -p $BASE/app/Console/Commands
touch $BASE/app/Console/Commands/ExpireReservations.php
touch $BASE/app/Console/Commands/SendReminders.php

# ── app/Exceptions
mkdir -p $BASE/app/Exceptions
touch $BASE/app/Exceptions/Handler.php

# ── app/Http/Controllers
mkdir -p $BASE/app/Http/Controllers/Auth
touch $BASE/app/Http/Controllers/Auth/AuthController.php

mkdir -p $BASE/app/Http/Controllers/Utilisateurs
touch $BASE/app/Http/Controllers/Utilisateurs/UserController.php
touch $BASE/app/Http/Controllers/Utilisateurs/ProfilController.php

mkdir -p $BASE/app/Http/Controllers/Annonces
touch $BASE/app/Http/Controllers/Annonces/AnnonceController.php
touch $BASE/app/Http/Controllers/Annonces/CalendrierController.php

mkdir -p $BASE/app/Http/Controllers/Reservations
touch $BASE/app/Http/Controllers/Reservations/ReservationController.php

mkdir -p $BASE/app/Http/Controllers/Paiements
touch $BASE/app/Http/Controllers/Paiements/PaiementController.php
touch $BASE/app/Http/Controllers/Paiements/RemboursementController.php

mkdir -p $BASE/app/Http/Controllers/Evaluations
touch $BASE/app/Http/Controllers/Evaluations/EvaluationController.php

mkdir -p $BASE/app/Http/Controllers/Notifications
touch $BASE/app/Http/Controllers/Notifications/NotificationController.php

mkdir -p $BASE/app/Http/Controllers/Administration
touch $BASE/app/Http/Controllers/Administration/AdminController.php
touch $BASE/app/Http/Controllers/Administration/LitigeController.php

# ── app/Http/Middleware
mkdir -p $BASE/app/Http/Middleware
touch $BASE/app/Http/Middleware/VerifyHote.php
touch $BASE/app/Http/Middleware/VerifyAdmin.php

# ── app/Http/Requests
mkdir -p $BASE/app/Http/Requests/Annonces
touch $BASE/app/Http/Requests/Annonces/StoreAnnonceRequest.php
touch $BASE/app/Http/Requests/Annonces/UpdateAnnonceRequest.php

mkdir -p $BASE/app/Http/Requests/Reservations
touch $BASE/app/Http/Requests/Reservations/StoreReservationRequest.php

mkdir -p $BASE/app/Http/Requests/Paiements
touch $BASE/app/Http/Requests/Paiements/PaiementRequest.php

# ── app/Http/Resources
mkdir -p $BASE/app/Http/Resources/Annonces
touch $BASE/app/Http/Resources/Annonces/AnnonceResource.php
touch $BASE/app/Http/Resources/Annonces/AnnonceCollection.php

mkdir -p $BASE/app/Http/Resources/Reservations
touch $BASE/app/Http/Resources/Reservations/ReservationResource.php

mkdir -p $BASE/app/Http/Resources/Utilisateurs
touch $BASE/app/Http/Resources/Utilisateurs/UserResource.php

# ── app/Models
mkdir -p $BASE/app/Models/Utilisateurs
touch $BASE/app/Models/Utilisateurs/User.php
touch $BASE/app/Models/Utilisateurs/Profil.php
touch $BASE/app/Models/Utilisateurs/Session.php
touch $BASE/app/Models/Utilisateurs/VerificationIdentite.php

mkdir -p $BASE/app/Models/Annonces
touch $BASE/app/Models/Annonces/Annonce.php
touch $BASE/app/Models/Annonces/Calendrier.php
touch $BASE/app/Models/Annonces/CategorieGeographique.php

mkdir -p $BASE/app/Models/Reservations
touch $BASE/app/Models/Reservations/Reservation.php
touch $BASE/app/Models/Reservations/RecapitulatifReservation.php

mkdir -p $BASE/app/Models/Paiements
touch $BASE/app/Models/Paiements/Paiement.php
touch $BASE/app/Models/Paiements/Remboursement.php
touch $BASE/app/Models/Paiements/Versement.php
touch $BASE/app/Models/Paiements/Recu.php

mkdir -p $BASE/app/Models/Evaluations
touch $BASE/app/Models/Evaluations/Evaluation.php
touch $BASE/app/Models/Evaluations/NoteDetaillee.php

mkdir -p $BASE/app/Models/Notifications
touch $BASE/app/Models/Notifications/Notification.php

mkdir -p $BASE/app/Models/Administration
touch $BASE/app/Models/Administration/Administrateur.php
touch $BASE/app/Models/Administration/TicketLitige.php

# ── app/Policies
mkdir -p $BASE/app/Policies
touch $BASE/app/Policies/AnnoncePolicy.php
touch $BASE/app/Policies/ReservationPolicy.php

# ── app/Services
mkdir -p $BASE/app/Services/Utilisateurs
touch $BASE/app/Services/Utilisateurs/UserService.php
touch $BASE/app/Services/Utilisateurs/VerificationService.php

mkdir -p $BASE/app/Services/Annonces
touch $BASE/app/Services/Annonces/AnnonceService.php
touch $BASE/app/Services/Annonces/CalendrierService.php

mkdir -p $BASE/app/Services/Reservations
touch $BASE/app/Services/Reservations/ReservationService.php
touch $BASE/app/Services/Reservations/StatutService.php

mkdir -p $BASE/app/Services/Paiements
touch $BASE/app/Services/Paiements/PaiementService.php
touch $BASE/app/Services/Paiements/RemboursementService.php

mkdir -p $BASE/app/Services/Evaluations
touch $BASE/app/Services/Evaluations/EvaluationService.php

mkdir -p $BASE/app/Services/Notifications
touch $BASE/app/Services/Notifications/NotificationService.php

# ── app/Repositories
mkdir -p $BASE/app/Repositories/Interfaces
touch $BASE/app/Repositories/Interfaces/ReservationRepositoryInterface.php
touch $BASE/app/Repositories/Interfaces/AnnonceRepositoryInterface.php

mkdir -p $BASE/app/Repositories/Eloquent
touch $BASE/app/Repositories/Eloquent/ReservationRepository.php
touch $BASE/app/Repositories/Eloquent/AnnonceRepository.php

# ── app/Events
mkdir -p $BASE/app/Events
touch $BASE/app/Events/ReservationConfirmee.php
touch $BASE/app/Events/ReservationAnnulee.php
touch $BASE/app/Events/PaiementEffectue.php

# ── app/Listeners
mkdir -p $BASE/app/Listeners
touch $BASE/app/Listeners/EnvoyerConfirmationEmail.php
touch $BASE/app/Listeners/MettreAJourCalendrier.php
touch $BASE/app/Listeners/VerserMontantHote.php

# ── app/Jobs
mkdir -p $BASE/app/Jobs
touch $BASE/app/Jobs/ProcessPaiement.php
touch $BASE/app/Jobs/EnvoyerRecapitulatif.php
touch $BASE/app/Jobs/ExpirerDemande.php

# ── app/Mail
mkdir -p $BASE/app/Mail
touch $BASE/app/Mail/ConfirmationReservation.php
touch $BASE/app/Mail/RecuPaiement.php

# ── app/Notifications
mkdir -p $BASE/app/Notifications
touch $BASE/app/Notifications/StatutReservationChange.php

# ── database
mkdir -p $BASE/database/migrations
touch $BASE/database/migrations/2024_01_01_000001_create_users_table.php
touch $BASE/database/migrations/2024_01_01_000002_create_profils_table.php
touch $BASE/database/migrations/2024_01_01_000003_create_verification_identites_table.php
touch $BASE/database/migrations/2024_01_01_000004_create_sessions_table.php
touch $BASE/database/migrations/2024_01_01_000005_create_categories_geographiques_table.php
touch $BASE/database/migrations/2024_01_01_000006_create_politique_annulations_table.php
touch $BASE/database/migrations/2024_01_01_000007_create_annonces_table.php
touch $BASE/database/migrations/2024_01_01_000008_create_calendriers_table.php
touch $BASE/database/migrations/2024_01_01_000009_create_reservations_table.php
touch $BASE/database/migrations/2024_01_01_000010_create_recapitulatif_reservations_table.php
touch $BASE/database/migrations/2024_01_01_000011_create_paiements_table.php
touch $BASE/database/migrations/2024_01_01_000012_create_remboursements_table.php
touch $BASE/database/migrations/2024_01_01_000013_create_versements_table.php
touch $BASE/database/migrations/2024_01_01_000014_create_recus_table.php
touch $BASE/database/migrations/2024_01_01_000015_create_evaluations_table.php
touch $BASE/database/migrations/2024_01_01_000016_create_note_detaillees_table.php
touch $BASE/database/migrations/2024_01_01_000017_create_ticket_litiges_table.php
touch $BASE/database/migrations/2024_01_01_000018_create_notifications_table.php
touch $BASE/database/migrations/2024_01_01_000019_create_administrateurs_table.php

mkdir -p $BASE/database/seeders
touch $BASE/database/seeders/DatabaseSeeder.php
touch $BASE/database/seeders/CategoriesGeographiquesSeeder.php
touch $BASE/database/seeders/PolitiquesAnnulationSeeder.php

mkdir -p $BASE/database/factories
touch $BASE/database/factories/UserFactory.php
touch $BASE/database/factories/AnnonceFactory.php

# ── routes
mkdir -p $BASE/routes
touch $BASE/routes/api.php
touch $BASE/routes/web.php
touch $BASE/routes/console.php

# ── config
mkdir -p $BASE/config
touch $BASE/config/payment.php
touch $BASE/config/hebergement.php

# ── storage
mkdir -p $BASE/storage/app/public/annonces
mkdir -p $BASE/storage/app/public/identites

# ── tests
mkdir -p $BASE/tests/Feature
touch $BASE/tests/Feature/ReservationTest.php
touch $BASE/tests/Feature/PaiementTest.php
touch $BASE/tests/Feature/EvaluationTest.php

mkdir -p $BASE/tests/Unit
touch $BASE/tests/Unit/RemboursementCalculTest.php
touch $BASE/tests/Unit/StatutReservationTest.php

# ── root files
touch $BASE/.env.example
touch $BASE/docker-compose.yml
touch $BASE/README.md

echo ""
echo "Done! Structure generated inside ./$BASE/"
echo "Run: cd $BASE && tree"
