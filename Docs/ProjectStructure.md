Accommity/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       ├── ExpireReservations.php       # RG15 - auto-expire after 24h
│   │       └── SendReminders.php            # RO07 - 48h/24h reminders
│   ├── Exceptions/
│   │   └── Handler.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php
│   │   │   ├── Utilisateurs/
│   │   │   │   ├── UserController.php
│   │   │   │   └── ProfilController.php
│   │   │   ├── Annonces/
│   │   │   │   ├── AnnonceController.php
│   │   │   │   └── CalendrierController.php
│   │   │   ├── Reservations/
│   │   │   │   └── ReservationController.php
│   │   │   ├── Paiements/
│   │   │   │   ├── PaiementController.php
│   │   │   │   └── RemboursementController.php
│   │   │   ├── Evaluations/
│   │   │   │   └── EvaluationController.php
│   │   │   ├── Notifications/
│   │   │   │   └── NotificationController.php
│   │   │   └── Administration/
│   │   │       ├── AdminController.php
│   │   │       └── LitigeController.php
│   │   ├── Middleware/
│   │   │   ├── VerifyHote.php
│   │   │   └── VerifyAdmin.php
│   │   ├── Requests/
│   │   │   ├── Annonces/
│   │   │   │   ├── StoreAnnonceRequest.php
│   │   │   │   └── UpdateAnnonceRequest.php
│   │   │   ├── Reservations/
│   │   │   │   └── StoreReservationRequest.php
│   │   │   └── Paiements/
│   │   │       └── PaiementRequest.php
│   │   └── Resources/
│   │       ├── Annonces/
│   │       │   ├── AnnonceResource.php
│   │       │   └── AnnonceCollection.php
│   │       ├── Reservations/
│   │       │   └── ReservationResource.php
│   │       └── Utilisateurs/
│   │           └── UserResource.php
│   ├── Models/
│   │   ├── Utilisateurs/
│   │   │   ├── User.php
│   │   │   ├── Profil.php
│   │   │   ├── Session.php
│   │   │   └── VerificationIdentite.php
│   │   ├── Annonces/
│   │   │   ├── Annonce.php
│   │   │   ├── Calendrier.php
│   │   │   └── CategorieGeographique.php
│   │   ├── Reservations/
│   │   │   ├── Reservation.php
│   │   │   └── RecapitulatifReservation.php
│   │   ├── Paiements/
│   │   │   ├── Paiement.php
│   │   │   ├── Remboursement.php
│   │   │   ├── Versement.php
│   │   │   └── Recu.php
│   │   ├── Evaluations/
│   │   │   ├── Evaluation.php
│   │   │   └── NoteDetaillee.php
│   │   ├── Notifications/
│   │   │   └── Notification.php
│   │   └── Administration/
│   │       ├── Administrateur.php
│   │       └── TicketLitige.php
│   ├── Policies/
│   │   ├── AnnoncePolicy.php
│   │   └── ReservationPolicy.php
│   ├── Services/
│   │   ├── Utilisateurs/
│   │   │   ├── UserService.php
│   │   │   └── VerificationService.php
│   │   ├── Annonces/
│   │   │   ├── AnnonceService.php
│   │   │   └── CalendrierService.php
│   │   ├── Reservations/
│   │   │   ├── ReservationService.php      # handles full status machine RG13
│   │   │   └── StatutService.php
│   │   ├── Paiements/
│   │   │   ├── PaiementService.php
│   │   │   └── RemboursementService.php    # flexible/modérée/stricte RG17
│   │   ├── Evaluations/
│   │   │   └── EvaluationService.php
│   │   └── Notifications/
│   │       └── NotificationService.php
│   ├── Repositories/
│   │   ├── Interfaces/
│   │   │   ├── ReservationRepositoryInterface.php
│   │   │   └── AnnonceRepositoryInterface.php
│   │   └── Eloquent/
│   │       ├── ReservationRepository.php
│   │       └── AnnonceRepository.php
│   ├── Events/
│   │   ├── ReservationConfirmee.php
│   │   ├── ReservationAnnulee.php
│   │   └── PaiementEffectue.php
│   ├── Listeners/
│   │   ├── EnvoyerConfirmationEmail.php
│   │   ├── MettreAJourCalendrier.php       # RG19
│   │   └── VerserMontantHote.php           # RG22
│   ├── Jobs/
│   │   ├── ProcessPaiement.php
│   │   ├── EnvoyerRecapitulatif.php        # RO05
│   │   └── ExpirerDemande.php              # RG15 - 24h expiry
│   ├── Mail/
│   │   ├── ConfirmationReservation.php
│   │   └── RecuPaiement.php                # RG26
│   └── Notifications/
│       └── StatutReservationChange.php     # RO06
│
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_profils_table.php
│   │   ├── 2024_01_01_000003_create_verification_identites_table.php
│   │   ├── 2024_01_01_000004_create_sessions_table.php
│   │   ├── 2024_01_01_000005_create_categories_geographiques_table.php
│   │   ├── 2024_01_01_000006_create_politique_annulations_table.php
│   │   ├── 2024_01_01_000007_create_annonces_table.php
│   │   ├── 2024_01_01_000008_create_calendriers_table.php
│   │   ├── 2024_01_01_000009_create_reservations_table.php
│   │   ├── 2024_01_01_000010_create_recapitulatif_reservations_table.php
│   │   ├── 2024_01_01_000011_create_paiements_table.php
│   │   ├── 2024_01_01_000012_create_remboursements_table.php
│   │   ├── 2024_01_01_000013_create_versements_table.php
│   │   ├── 2024_01_01_000014_create_recus_table.php
│   │   ├── 2024_01_01_000015_create_evaluations_table.php
│   │   ├── 2024_01_01_000016_create_note_detaillees_table.php
│   │   ├── 2024_01_01_000017_create_ticket_litiges_table.php
│   │   ├── 2024_01_01_000018_create_notifications_table.php
│   │   └── 2024_01_01_000019_create_administrateurs_table.php
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── CategoriesGeographiquesSeeder.php
│   │   └── PolitiquesAnnulationSeeder.php
│   └── factories/
│       ├── UserFactory.php
│       └── AnnonceFactory.php
│
├── routes/
│   ├── api.php
│   ├── web.php
│   └── console.php
│
├── config/
│   ├── sanctum.php
│   ├── permission.php
│   ├── payment.php                          # Stripe keys, commission rates
│   └── hebergement.php                      # seuils note, délais, politiques
│
├── storage/
│   └── app/
│       └── public/
│           ├── annonces/                    # photos hébergements
│           └── identites/                   # pièces d'identité (private)
│
├── tests/
│   ├── Feature/
│   │   ├── ReservationTest.php
│   │   ├── PaiementTest.php
│   │   └── EvaluationTest.php
│   └── Unit/
│       ├── RemboursementCalculTest.php
│       └── StatutReservationTest.php
│
├── .env
├── .env.example
├── .gitignore
├── docker-compose.yml
└── README.md
