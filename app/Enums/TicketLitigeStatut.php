<?php

namespace App\Enums;

enum TicketLitigeStatut: string
{
    case EN_COURS = 'En cours';
    case CLOTURE = 'Clôturé';
}
