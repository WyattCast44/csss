<?php

namespace App\Enums;

enum RankType: string
{
    case OFFICER = 'officer';
    case ENLISTED = 'enlisted';
    case CIVILIAN = 'civilian';
    case OTHER = 'other';
}
