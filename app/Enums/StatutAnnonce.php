<?php

namespace App\Enums;

enum StatutAnnonce: string
{
    case EN_VERIFICATION = 'En cours de vérification';
    case PUBLIE = 'Publié';
    case SUSPENDU = 'Suspendu';
    case DESACTIVE = 'Désactivé';
    case REJETE = 'Rejeté';
}
