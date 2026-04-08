<?php

namespace App\Services\Evaluations;

use App\Models\Evaluations\Evaluation;
use App\Models\Evaluations\NoteDetaillee;
use App\Models\Reservations\Reservation;
use App\Models\Administration\TicketLitige;
use App\Enums\StatutReservation;
use App\Enums\TypeAuteur;
use Illuminate\Support\Facades\DB;

class EvaluationService
{
    /**
     * Délai maximum (en jours) après lequel un avis ne peut plus être modifié/supprimé (RO12).
     */
    private const DELAI_MODIFICATION_JOURS = 14;

    /**
     * creer(données, auteur, reservation) : Evaluation
     *
     * Vérifie :
     * - RG27 : la réservation est au statut "Terminée"
     * - RO11 : un seul avis par réservation par rôle (type_auteur)
     * - RG28 : bilatéral, le type_auteur est déterminé automatiquement
     *
     * @throws \Exception
     */
    public function creer(array $data, string $idAuteur, Reservation $reservation): Evaluation
    {
        // RG27 : vérifier que la réservation est terminée
        if ($reservation->statut !== StatutReservation::TERMINEE) {
            throw new \Exception("Vous ne pouvez évaluer qu'après la fin du séjour (RG27).");
        }

        // RG28 : déterminer le rôle de l'auteur (voyageur ou hôte)
        if ($reservation->id_voyageur === $idAuteur) {
            $typeAuteur = TypeAuteur::VOYAGEUR;
            $idCible    = $reservation->id_hote;
            $idAnnonce  = $reservation->id_annonce;
        } elseif ($reservation->id_hote === $idAuteur) {
            $typeAuteur = TypeAuteur::HOTE;
            $idCible    = $reservation->id_voyageur;
            $idAnnonce  = null; // l'hôte note le voyageur, pas l'annonce
        } else {
            throw new \Exception("Vous n'êtes pas impliqué dans cette réservation.");
        }

        // RO11 : vérifier l'unicité (un seul avis par réservation par rôle)
        $existant = Evaluation::where('id_reservation', $reservation->id_reservation)
            ->where('id_auteur', $idAuteur)
            ->where('type_auteur', $typeAuteur)
            ->exists();

        if ($existant) {
            throw new \Exception("Vous avez déjà soumis un avis pour cette réservation (RO11).");
        }

        return DB::transaction(function () use ($data, $idAuteur, $reservation, $typeAuteur, $idCible, $idAnnonce) {
            // Calculer la note globale (RG30) — moyenne des 5 critères
            $noteGlobale = round(
                ($data['proprete'] + $data['communication'] + $data['emplacement']
                    + $data['rapport_qualite_prix'] + $data['exactitude']) / 5,
                2
            );

            // Créer l'évaluation
            $evaluation = Evaluation::create([
                'id_reservation' => $reservation->id_reservation,
                'id_auteur'      => $idAuteur,
                'id_cible'       => $idCible,
                'id_annonce'     => $idAnnonce,
                'type_auteur'    => $typeAuteur,
                'note'           => $noteGlobale,
                'commentaire'    => $data['commentaire'],
                'est_signale'    => false,
            ]);

            // Créer la note détaillée (composition)
            NoteDetaillee::create([
                'id_evaluation'        => $evaluation->id_evaluation,
                'proprete'             => $data['proprete'],
                'communication'        => $data['communication'],
                'emplacement'          => $data['emplacement'],
                'rapport_qualite_prix' => $data['rapport_qualite_prix'],
                'exactitude'           => $data['exactitude'],
            ]);

            return $evaluation->load('noteDetaillee');
        });
    }

    /**
     * modifier(id, données, idAuteur) : Evaluation
     *
     * RO12 : vérifie que c'est l'auteur et que le délai n'est pas dépassé.
     *
     * @throws \Exception
     */
    public function modifier(string $idEvaluation, array $data, string $idAuteur): Evaluation
    {
        $evaluation = Evaluation::with('noteDetaillee')->findOrFail($idEvaluation);

        // Vérifier que c'est l'auteur
        if ($evaluation->id_auteur !== $idAuteur) {
            throw new \Exception("Vous n'êtes pas l'auteur de cet avis.");
        }

        // RO12 : vérifier le délai de modification
        if ($evaluation->date_creation->diffInDays(now()) > self::DELAI_MODIFICATION_JOURS) {
            throw new \Exception("Le délai de modification de " . self::DELAI_MODIFICATION_JOURS . " jours est dépassé (RO12).");
        }

        return DB::transaction(function () use ($evaluation, $data) {
            // Recalculer la note globale (RG30)
            $noteGlobale = round(
                ($data['proprete'] + $data['communication'] + $data['emplacement']
                    + $data['rapport_qualite_prix'] + $data['exactitude']) / 5,
                2
            );

            $evaluation->update([
                'note'        => $noteGlobale,
                'commentaire' => $data['commentaire'],
            ]);

            $evaluation->noteDetaillee->update([
                'proprete'             => $data['proprete'],
                'communication'        => $data['communication'],
                'emplacement'          => $data['emplacement'],
                'rapport_qualite_prix' => $data['rapport_qualite_prix'],
                'exactitude'           => $data['exactitude'],
            ]);

            return $evaluation->fresh(['noteDetaillee']);
        });
    }

    /**
     * supprimer(id, idAuteur) : void
     *
     * RO12 : suppression par l'auteur dans le délai défini.
     *
     * @throws \Exception
     */
    public function supprimer(string $idEvaluation, string $idAuteur): void
    {
        $evaluation = Evaluation::findOrFail($idEvaluation);

        if ($evaluation->id_auteur !== $idAuteur) {
            throw new \Exception("Vous n'êtes pas l'auteur de cet avis.");
        }

        if ($evaluation->date_creation->diffInDays(now()) > self::DELAI_MODIFICATION_JOURS) {
            throw new \Exception("Le délai de suppression de " . self::DELAI_MODIFICATION_JOURS . " jours est dépassé (RO12).");
        }

        $evaluation->delete();
    }

    /**
     * signalerEvaluation(id, motif, idDeclarant) : void
     *
     * RO10 : marque l'avis comme signalé + génère automatiquement un TicketLitige.
     */
    public function signalerEvaluation(string $idEvaluation, string $motif, string $idDeclarant): void
    {
        DB::transaction(function () use ($idEvaluation, $motif, $idDeclarant) {
            $evaluation = Evaluation::findOrFail($idEvaluation);

            // Marquer comme signalé
            $evaluation->signalerEvaluation($motif);

            // RO10 : générer automatiquement un ticket de litige
            TicketLitige::genererTicket($idEvaluation, $idDeclarant, $motif);
        });
    }

    /**
     * getEvaluations(idUtilisateur) : Collection
     * Évaluations reçues par un utilisateur.
     */
    public function getEvaluations(string $idUtilisateur)
    {
        return Evaluation::getEvaluations($idUtilisateur);
    }

    /**
     * getEvaluationsSignalees() : Collection
     * Admin : liste des avis signalés.
     */
    public function getEvaluationsSignalees()
    {
        return Evaluation::getEvaluationsSignalees();
    }

    /**
     * supprimerEvaluation(id) : void
     * RG31 : suppression par l'admin après investigation.
     */
    public function supprimerEvaluation(string $idEvaluation): void
    {
        $evaluation = Evaluation::findOrFail($idEvaluation);
        $evaluation->delete();
    }

    /**
     * conserverEvaluation(id) : void
     * Admin : enlève le flag de signalement.
     */
    public function conserverEvaluation(string $idEvaluation): void
    {
        $evaluation = Evaluation::findOrFail($idEvaluation);
        $evaluation->conserverEvaluation();
    }

    /**
     * Vérifie si un utilisateur peut évaluer une réservation donnée.
     * Retourne true/false + le type d'auteur.
     */
    public function peutEvaluer(string $idAuteur, Reservation $reservation): array
    {
        // RG27
        if ($reservation->statut !== StatutReservation::TERMINEE) {
            return ['peut' => false, 'raison' => 'La réservation n\'est pas encore terminée.'];
        }

        // Déterminer le rôle
        if ($reservation->id_voyageur === $idAuteur) {
            $typeAuteur = TypeAuteur::VOYAGEUR;
        } elseif ($reservation->id_hote === $idAuteur) {
            $typeAuteur = TypeAuteur::HOTE;
        } else {
            return ['peut' => false, 'raison' => 'Vous n\'êtes pas impliqué dans cette réservation.'];
        }

        // RO11
        $existant = Evaluation::where('id_reservation', $reservation->id_reservation)
            ->where('id_auteur', $idAuteur)
            ->where('type_auteur', $typeAuteur)
            ->exists();

        if ($existant) {
            return ['peut' => false, 'raison' => 'Vous avez déjà soumis un avis pour cette réservation.'];
        }

        return ['peut' => true, 'type_auteur' => $typeAuteur];
    }
}
