<?php

namespace App\Enums;

enum TypePolitiqueAnnulation: string
{
    case FLEXIBLE = 'flexible';
    case MODEREE = 'modérée';
    case STRICTE = 'stricte';
}
