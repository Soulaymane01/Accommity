# les methodes du Diagramme de Classes Général — Plateforme de Gestion d'Hébergements

```
' ══════════════════════════════════════════
' PACKAGE : PK_Utilisateurs
' ══════════════════════════════════════════
package "PK_Utilisateurs" {

    class Utilisateur {

        + verifierEmailUnique(email) : bool
        + creerCompte() : void
        + getRoleUtilisateur() : string
        + getStatutVerification() : string
        + mettreAJourStatut(statut) : void
        + redirigerVers(page) : void
        + listerEvaluations(idUtilisateur) : list
        + getUtilisateurById(idUtilisateur, role) : void
        + updateUser(idUtilisateur) : void
        + deleteUser(idUtilisateur) : void
        + mettreAJourStatu(idUtilisateur, statut) : void
    }

    class Profil {

        + initialiserProfil() : void
        + ajouterPhoto(photo) : void
        + ajouterDescription(description) : void
        + getProfilActuel() : Profil
        + marquerProfilComplete() : void
    }

    class Session {

        + ouvrirSession() : void
        + fermerSession() : void
    }

    class VerificationIdentite {

        + soumettreDocuments() : void
        + enregistrerDossier() : void
        + visualiserDossier() : VerificationIdentite
        + validerVerification() : void
        + rejeterVerification(motif) : void
    }
}

' ══════════════════════════════════════════
' PACKAGE : PK_Annonces
' ══════════════════════════════════════════
package "PK_Annonces" {

    class Annonce {

        + creerAnnonce() : void
        + getDetailsAnnonce() : Annonce
        + verifierProprietaire(idHote) : bool
        + getStatutAnnonce() : string
        + modifierInformations() : void
        + modifierPrix(prixParNuit) : void
        + modifierModeReservation(mode) : void
        + mettreAJourCategorie(idCategorie) : void
        + mettreAJourPolitique(idPolitique) : void
        + desactiverAnnonce() : void
        + rechercherAnnonces(idCategorie,\ndateArrivee, dateDepart,\nnbVoyageurs) : list
        + setStatusAnnonce(idAnnonce, statut) : void
        + getAnnonces() : list
        + getAnnoncesById() : list
        + filtrerAnnonces(statut) : list
        + getAnnoncesParHote(idHote) : list
        + getAnnoncesDisponibles() : list
        + calculerNoteGlobale() : decimal
        + suspendre() : void
    }

    class CategorieGeographique {

        + ajouterCategorie(ville, region, pays) : void
        + modifierCategorie(ville, region, pays) : void
        + rechercherParLocalisation(ville,\nregion, pays) : list
    }

    class Calendrier {

        + initialiserCalendrier(idAnnonce) : void
        + getCalendrier(idAnnonce) : Calendrier
        + getDisponibilites(idAnnonce) : list
        + verifierDisponibilite(dateArrivee,\ndateDepart) : bool
        + bloquerDatesManuel(dateDebut, dateFin) : void
        + debloquerDates(dateDebut, dateFin) : void
        + supprimerCalendrier(idAnnonce) : void
    }

    class PolitiqueAnnulation {

        + definirPolitiqueAnnulation(type) : void
        + modifierPolitiqueAnnulation(type) : void
        + calculerRemboursement(montant,\ndateAnnulation) : decimal
        + remboursementPartiel(idReservation) : void
        + remboursementIntegral(idReservation) : void
    }
}

' ══════════════════════════════════════════
' PACKAGE : PK_Reservations
' ══════════════════════════════════════════
package "PK_Reservations" {

    class Reservation {

        + verifierDisponibiliteEtCapacite(\nidAnnonce, dates, nbVoy) : bool
        + verifierVoyageurNonHote(\nidVoyageur, idAnnonce) : bool
        + getModeReservation(idAnnonce) : string
        + creerReservation(idAnnonce,\ndates, nbVoy, mode) : Reservation
        + setStatutEnAttente() : void
        + confirmerReservationAutomatiquement() : void
        + setStatutConfirmee() : void
        + setStatutEnCours() : void
        + setStatutTerminee() : void
        + setStatutRefusee() : void
        + setStatutAnnulee(acteur) : void
        + expireReservation() : void
        + verifierStatutTerminee() : bool
        + bloquerDatesCalendrier(idAnnonce, dates) : void
        + debloquerDatesCalendrier(idAnnonce, dates) : void
        + envoyerRappels() : void
        + getReservations() : list
        + getStatut() : string
        + getMontantTotal() : decimal
        + getReservationById(idReservation) : Reservation
        + verserHote() : void
        + filtrerReservations(statut) : list
        + determinerActeurAnnulation() : string
    }

    class RecapitulatifReservation {

        + genererRecapitulatif(idReservation) : RecapitulatifReservation
        + envoyerRecu(idVoyageur) : void
        + getDetails() : string
    }
}

' ══════════════════════════════════════════
' PACKAGE : PK_Paiements
' ══════════════════════════════════════════
package "PK_Paiements" {

    class Paiement {

        + effectuerPaiement(idRes, montant) : void
        + getPaiementsById(idVoyageur) : list
        + getPaiement() : list
        + filtrerPaiements(statut) : list
        + getPaiementById(idPaiement) : Paiement
    }

    class Versement {

        + initierVersement(idRes) : void
        + enregistrerVersement() : void
        + getVersements(idHote) : list
        + filtrerVersements(statut) : list
    }

    class Remboursement {

        + getRemboursementsParVoyageur(idVoyageur) : list
        + filtrerRemboursements(statut) : list
    }

    class Recu {

        + genererRecu() : void
    }
}

' ══════════════════════════════════════════
' PACKAGE : PK_Evaluations
' ══════════════════════════════════════════
package "PK_Evaluations" {

    class Evaluation {

        + creer(note, commentaire, auteur) : void
        + enregistrer() : void
        + modifier(note, commentaire) : void
        + supprimer() : void
        + lireDetails() : Evaluation
        + getEvaluations(idUtilisateur) : list
        + getEvaluationById(idEvaluation) : Evaluation
        + signalerEvaluation(idEvaluation,\nmotifSignalement) : void
        + getEvaluationsSignalees() : list
        + supprimerEvaluation(idAvis) : void
        + conserverEvaluation() : void
    }

    class NoteDetaillee {
        -- aucune methode
    }

    class TicketLitige {

        + genererTicket(idEvaluation,\nidDeclarant, motif) : TicketLitige
        + getLitiges() : list
        + getLitigeById(idLitige) : TicketLitige
        + modifierStatutLitige(idLitige, statut) : void
        + getStatsLitiges() : map
    }
}

' ══════════════════════════════════════════
' PACKAGE : PK_Notifications
' ══════════════════════════════════════════
package "PK_Notifications" {

    class Notification {

        + getNotifications(idUtilisateur) : list
        + creerNotification(idDestinataire,\ntitre, contenu, type) : void
        + marquerCommeLue() : void
        + envoyerNotification() : void
    }
}

' ══════════════════════════════════════════
' PACKAGE : PK_Administration
' ══════════════════════════════════════════
package "PK_Administration" {

    class Administrateur {
     -- aucune methode
    }
}
Note : admin herite de Utilisateur, donc il utilise ces methodes
```
