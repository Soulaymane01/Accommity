<?php

namespace App\Enums;

enum VerificationStatut: string
{
    case EN_COURS = 'En cours de traitement';
    case VALIDE = 'Validé';
    case REJETE = 'rejeté';
}
