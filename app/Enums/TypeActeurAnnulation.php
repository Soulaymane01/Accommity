<?php

namespace App\Enums;

enum TypeActeurAnnulation: string
{
    case VOYAGEUR = 'voyageur';
    case HOTE = 'hote';
    case SYSTEME = 'systeme';
}
