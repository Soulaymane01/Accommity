# the complete class diagram

```
@startuml DiagrammeClassesGeneral
title Diagramme de Classes Général — Plateforme de Gestion d'Hébergements

top to bottom direction

skinparam classBackgroundColor #EAF4FB
skinparam classBorderColor #2471A3
skinparam classArrowColor #1A5276
skinparam packageBackgroundColor #F4F9FF
skinparam packageBorderColor #2471A3
skinparam wrapWidth 200
skinparam maxMessageSize 100
skinparam classAttributeIconSize 0

' ══════════════════════════════════════════
' PACKAGE : PK_Utilisateurs
' ══════════════════════════════════════════
package "PK_Utilisateurs" {

    class Utilisateur {
        + id_utilisateur : UUID
        + nom : String
        + prenom : String
        + email : String
        - mot_de_passe : String
        + telephone : String
        + est_hote : Boolean
        + est_voyageur : Boolean
        + date_creation : Timestamp
        --
        + verifierEmailUnique(email : String) : Boolean
        + creerCompte() : void
        + redirigerVers(): void
        + getRoleUtilisateur() : String
        + getStatutVerification() : String
        + listerEvaluations() : List<Evaluation>
        + getUtilisateurById(id : UUID) : Utilisateur
        + updateUser() : void
        + deleteUser() : void
    }

    class Profil {
        + id_profil : UUID
        + photo_url : String
        + description : String
        + note_moyenne : Float
        --
        + initialiserProfil() : void
        + ajouterPhoto(photo_url : String) : void
        + ajouterDescription(description : String) : void
        + getProfilActuel() : Profil
        + marquerProfilComplete() : void
    }

    class Session {
        + id_session : UUID
        - token : String
        + date_expiration : Timestamp
        --
        + ouvrirSession() : void
        + fermerSession() : void
    }

    class VerificationIdentite {
        + id_verification : UUID
        + type_piece : String
        + chemin_document: String
        + statut : String
        + date_soumission : Timestamp
        + motif_rejet : String
        --
        + soumettreDocuments() : void
        + enregistrerDossier() : void
        + visualiserDossier() : VerificationIdentite
        + validerVerification() : void
        + rejeterVerification(motif : String) : void
    }
}

' ══════════════════════════════════════════
' PACKAGE : PK_Administration
' ══════════════════════════════════════════
package "PK_Administration" {

    class Administrateur {
        + id_admin : UUID
        + email : String
        - motDePasse : String
        + derniere_connexion : Datetime
    }
}

' ══════════════════════════════════════════
' PACKAGE : PK_Annonces
' ══════════════════════════════════════════
package "PK_Annonces" {

    class CategorieGeographique {
        + id_categorie : UUID
        + ville : String
        + region : String
        + pays : String
        --
        + ajouterCategorie(ville : String, region : String, pays : String) : void
        + modifierCategorie(ville : String, region : String, pays : String) : void
        + rechercherParLocalisation(ville : String, region : String, pays : String) : List<Annonce>
    }

    class PolitiqueAnnulation {
        + id_politique : UUID
        + type_politique : TypePolitique
        + delai_remb_total : Integer
        + delai_remb_partiel : Integer
        + taux_remb_partiel : Decimal
        + description : String
        --
        + definirPolitique(type : TypePolitique) : void
        + modifierPolitique(type : TypePolitique) : void
        + calculerRemboursement(montant : Decimal, dateAnnulation : Date) : Decimal
        + remboursementPartiel(reservation : Reservation) : void
        + remboursementIntegral(reservation : Reservation) : void
    }

    class Annonce {
        + id_annonce : UUID
        + titre : String
        + description : String
        + photo_url : String
        + type_logement : String
        + adresse : String
        + capacite : Integer
        + tarif_nuit : Decimal
        + mode_reservation : ModeReservation
        + statut : StatutAnnonce
        + equipements : String
        + reglement_interieur : String
        + date_creation : Timestamp
        --
        + creerAnnonce() : void
        + getDetailsAnnonce() : Annonce
        + verifierProprietaire(hote : Utilisateur) : Boolean
        + modifierInformations() : void
        + modifierPrix(tarif_nuit : Decimal) : void
        + modifierModeReservation(mode : ModeReservation) : void
        + mettreAJourCategorie(categorie : CategorieGeographique) : void
        + mettreAJourPolitique(politique : PolitiqueAnnulation) : void
        + desactiverAnnonce() : void
        + rechercherAnnonces(categorie : CategorieGeographique,\ndateArrivee : Date, dateDepart : Date,\nnbVoyageurs : Integer) : List<Annonce>
        + getAnnonces() : List<Annonce>
        + getAnnoncesDisponibles() : List<Annonce>
        + calculerNoteGlobale() : Decimal
        + suspendre() : void
    }

    class Calendrier {
        + id_calendrier : UUID
        + date_debut : Date
        + date_fin : Date
        + est_disponible : Boolean
        + type_blockage : TypeBlockage
        --
        + initialiserCalendrier() : void
        + getDisponibilites() : List<Calendrier>
        + verifierDisponibilite(dateArrivee : Date, dateDepart : Date) : Boolean
        + bloquerDatesManuel(dateDebut : Date, dateFin : Date) : void
        + debloquerDates(dateDebut : Date, dateFin : Date) : void
        + supprimerCalendrier() : void
    }
}

' ══════════════════════════════════════════
' PACKAGE : PK_Reservations
' ══════════════════════════════════════════
package "PK_Reservations" {

    class Reservation {
        + id_reservation : UUID
        + date_arrivee : Date
        + date_depart : Date
        + nb_voyageurs : Integer
        + statut : StatutReservation
        + mode_reservation : ModeReservation
        + montant_total : Decimal
        + frais_service : Decimal
        + message_optionnel : String
        + acteur_annulation : ActeurAnnulation
        + date_creation : Timestamp
        + date_modification : Timestamp
        --
        + verifierDisponibiliteEtCapacite(annonce : Annonce,\ndates : Date, nbVoyageurs : Integer) : Boolean
        + verifierVoyageurNonHote(voyageur : Utilisateur,\nannonce : Annonce) : Boolean
        + getModeReservation() : ModeReservation
        + creerReservation(annonce : Annonce, dates : Date,\nnbVoyageurs : Integer, mode : ModeReservation) : Reservation
        + setStatutEnAttente() : void
        + confirmerReservationAutomatiquement() : void
        + setStatutConfirmee() : void
        + setStatutEnCours() : void
        + setStatutTerminee() : void
        + setStatutRefusee() : void
        + setStatutAnnulee(acteur : ActeurAnnulation) : void
        + expireReservation() : void
        + getReservationById(id : UUID) : Reservation
        + getReservations() : List<Reservation>
        + filtrerReservations(statut : StatutReservation) : List<Reservation>
        + determinerActeurAnnulation() : ActeurAnnulation
    }

    class Minuterie {
        + id_minuterie : UUID
        + type_minuterie : TypeMinuterie
        + date_echeance : Timestamp
        + est_active : Boolean
        --
        + creerMinuterie(type : TypeMinuterie, echeance : Timestamp) : void
        + desactiverMinuterie() : void
        + verifierEcheance() : Boolean
        + envoyerRappels() : void
    }

    class RecapitulatifReservation {
        + id_recapitulatif : UUID
        + details_sejour : String
        + montant_total : Decimal
        + frais_service : Decimal
        + montant_hote : Decimal
        + coordonnees_voyageur : String
        + coordonnees_hote : String
        + date_generation : Timestamp
        --
        + genererRecapitulatif(reservation : Reservation) : RecapitulatifReservation
        + envoyerRecu(voyageur : Utilisateur) : void
        + getDetails() : String
    }
}

' ══════════════════════════════════════════
' PACKAGE : PK_Paiements
' ══════════════════════════════════════════
package "PK_Paiements" {

    class Paiement {
        + id_paiement : UUID
        + montant : Decimal
        + date_transaction : Timestamp
        + methode_paiement : MethodePaiement
        + statut : StatutPaiement
        --
        + effectuerPaiement(reservation : Reservation, montant : Decimal) : void
        + getPaiementsParVoyageur(voyageur : Utilisateur) : List<Paiement>
        + getPaiements() : List<Paiement>
        + filtrerPaiements(statut : StatutPaiement) : List<Paiement>
        + getPaiementById(id : UUID) : Paiement
    }

    class Versement {
        + id_versement : UUID
        + montant : Decimal
        + date_versement : Timestamp
        + reference_bancaire : String
        + statut : StatutVersement
        --
        + initierVersement(reservation : Reservation) : void
        + enregistrerVersement() : void
        + getVersementsParHote(hote : Utilisateur) : List<Versement>
        + filtrerVersements(statut : StatutVersement) : List<Versement>
    }

    class Remboursement {
        + id_remboursement : UUID
        + montant : Decimal
        + date_remboursement : Timestamp
        + motif : MotifRemboursement
        --
        + getRemboursementsParVoyageur(voyageur : Utilisateur) : List<Remboursement>
        + filtrerRemboursements(statut : String) : List<Remboursement>
    }

    class Recu {
        + id_recu : UUID
        + numero_facture : String
        + chemin_pdf : String
        + date_generation : Timestamp
        --
        + genererRecu() : void
    }
}

' ══════════════════════════════════════════
' PACKAGE : PK_Evaluations
' ══════════════════════════════════════════
package "PK_Evaluations" {

    class Evaluation {
        + id_evaluation : UUID
        + type_auteur : TypeAuteur
        + note : Decimal
        + commentaire : String
        + est_signale : Boolean
        + motif_signalement : String
        + date_creation : Timestamp
        + date_modification : Timestamp
        --
        + creer(note : Decimal, commentaire : String, auteur : Utilisateur) : void
        + enregistrer() : void
        + modifier(note : Decimal, commentaire : String) : void
        + supprimer() : void
        + lireDetails() : Evaluation
        + getEvaluations(utilisateur : Utilisateur) : List<Evaluation>
        + getEvaluationById(id : UUID) : Evaluation
        + signalerEvaluation(motif : String) : void
        + getEvaluationsSignalees() : List<Evaluation>
        + conserverEvaluation() : void
    }

    class NoteDetaillee {
        + id_note : UUID
        + proprete : Decimal
        + communication : Decimal
        + emplacement : Decimal
        + rapport_qualite_prix : Decimal
        + exactitude : Decimal
    }

    class TicketLitige {
        + id_ticket : UUID
        + motif : String
        + statut : String
        + date_creation : Timestamp
        + date_cloture : Timestamp
        --
        + genererTicket(evaluation : Evaluation,\ndeclarant : Utilisateur, motif : String) : TicketLitige
        + getLitiges() : List<TicketLitige>
        + getLitigeById(id : UUID) : TicketLitige
        + modifierStatutLitige(statut : String) : void
        + getStatsLitiges() : Map
    }
}

' ══════════════════════════════════════════
' PACKAGE : PK_Notifications
' ══════════════════════════════════════════
package "PK_Notifications" {

    class Notification {
        + id_notification : UUID
        + titre : String
        + contenu : String
        + type_alerte : TypeAlerte
        + est_lue : Boolean
        + date_creation : Timestamp
        --
        + getNotifications(destinataire : Utilisateur) : List<Notification>
        + creerNotification(destinataire : Utilisateur,\ntitre : String, contenu : String,\ntype : TypeAlerte) : void
        + marquerCommeLue() : void
        + envoyerNotification() : void
    }
}

' ══════════════════════════════════════════════════════════════════
' RELATIONS — PK_Utilisateurs
' Profil et Session sont des composants du cycle de vie
' de l'Utilisateur (composition).
' VerificationIdentite existe indépendamment (agrégation).
' ══════════════════════════════════════════════════════════════════
Utilisateur "1" *-- "1"    Profil              : possède >
Utilisateur "1" *-- "0..*" Session             : ouvre >
Utilisateur "1" o-- "0..*" VerificationIdentite : soumet >

' ══════════════════════════════════════════════════════════════════
' RELATIONS — PK_Administration
' Administrateur est une spécialisation d'Utilisateur (héritage).
' ══════════════════════════════════════════════════════════════════
Administrateur --|> Utilisateur

' ══════════════════════════════════════════════════════════════════
' RELATIONS — PK_Annonces
' Calendrier est une partie intrinsèque de l'Annonce (composition).
' CategorieGeographique et PolitiqueAnnulation sont partagées
' entre plusieurs Annonces (associations dirigées).
' ══════════════════════════════════════════════════════════════════
Annonce "1"    *-- "1"    Calendrier            : possède >
Annonce "0..*" --> "1"    CategorieGeographique : appartient à >
Annonce "0..*" --> "0..1" PolitiqueAnnulation   : applique >

' ══════════════════════════════════════════════════════════════════
' RELATIONS — PK_Reservations
' Minuterie et RecapitulatifReservation n'existent que dans
' le contexte d'une Réservation (composition).
' PolitiqueAnnulation est une référence partagée (association).
' ══════════════════════════════════════════════════════════════════
Reservation "1"    *-- "0..1" Minuterie                : gère >
Reservation "1"    *-- "0..1" RecapitulatifReservation  : génère >
Reservation "0..*" -->  "0..1" PolitiqueAnnulation      : régit par >

' ══════════════════════════════════════════════════════════════════
' RELATIONS — PK_Paiements
' Recu n'existe que rattaché à un Paiement (composition).
' ══════════════════════════════════════════════════════════════════
Paiement "1" *-- "0..1" Recu : génère >

' ══════════════════════════════════════════════════════════════════
' RELATIONS — PK_Evaluations
' NoteDetaillee est une partie intrinsèque de l'Evaluation (composition).
' TicketLitige est déclenché par une Evaluation (association dirigée).
' ══════════════════════════════════════════════════════════════════
Evaluation "1" *-- "1"    NoteDetaillee : contient >
Evaluation "1" --> "0..1" TicketLitige  : génère >

' ══════════════════════════════════════════════════════════════════
' RELATIONS INTER-PACKAGES
' ══════════════════════════════════════════════════════════════════

' Utilisateur (hôte) publie des Annonces
Utilisateur "1" --> "0..*" Annonce : publie >\n{role=hote}

' Utilisateur → Reservations (deux rôles distincts)
Utilisateur "1" --> "0..*" Reservation : effectue >\n{role=voyageur}
Utilisateur "1" --> "0..*" Reservation : reçoit >\n{role=hote}

' Utilisateur → Paiements / Versements / Remboursements
Utilisateur "1" --> "0..*" Paiement      : paie >
Utilisateur "1" --> "0..*" Versement     : reçoit >
Utilisateur "1" --> "0..*" Remboursement : reçoit >

' Utilisateur → Evaluations (deux rôles distincts)
Utilisateur "1" --> "0..*" Evaluation : rédige >\n{role=auteur}
Utilisateur "1" --> "0..*" Evaluation : est évalué par >\n{role=cible}

' Utilisateur → Litiges et Notifications
Utilisateur "1" --> "0..*" TicketLitige : déclare >
Utilisateur "1" --> "0..*" Notification : reçoit >

' Annonce → Reservations et Evaluations
Annonce "1" --> "0..*" Reservation : fait l'objet de >
Annonce "1" --> "0..*" Evaluation  : reçoit >

' Reservation → Paiement, Versement, Remboursement, Evaluation
Reservation "1" --> "0..1" Paiement      : est payée par >
Reservation "1" --> "0..1" Versement     : déclenche >
Reservation "1" --> "0..1" Remboursement : peut générer >
Reservation "1" --> "0..*" Evaluation    : est à l'origine de >

' Administrateur → TicketLitige
Administrateur "1" --> "0..*" TicketLitige : traite >

@enduml
```