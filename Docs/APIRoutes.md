// Auth
POST   /api/register
POST   /api/login
POST   /api/logout

// Utilisateurs
GET    /api/users/{id}/profil
PUT    /api/users/{id}/profil
POST   /api/users/{id}/verification          // RG03

// Annonces
GET    /api/annonces                         // search + filter RG11
POST   /api/annonces                         // RG06, RG07
GET    /api/annonces/{id}
PUT    /api/annonces/{id}
DELETE /api/annonces/{id}
GET    /api/annonces/{id}/disponibilites     // RG08

// Réservations
POST   /api/reservations                     // RG12, RG14/15
GET    /api/reservations/{id}
POST   /api/reservations/{id}/confirmer      // hôte accept RG13
POST   /api/reservations/{id}/refuser        // hôte refuse RG13
POST   /api/reservations/{id}/annuler        // RG17

// Paiements
POST   /api/paiements                        // RG20
GET    /api/paiements/historique             // RO08
POST   /api/remboursements/{id}              // RG23/24

// Évaluations
POST   /api/evaluations                      // RG27/28
PUT    /api/evaluations/{id}                 // RO12

// Notifications
GET    /api/notifications

// Administration (admin middleware)
GET    /api/admin/users
DELETE /api/admin/users/{id}                 // RG05
GET    /api/admin/annonces
PUT    /api/admin/annonces/{id}/suspendre
GET    /api/admin/litiges
PUT    /api/admin/litiges/{id}
DELETE /api/admin/evaluations/{id}           // RG31
