<?php

namespace App\Enums;

enum TrainingFrequency: string
{
    case ANNUAL = 'annual';
    case BI_ANNUAL = 'bi-annual';
    case QUARTERLY = 'quarterly';
    case MONTHLY = 'monthly';
    case WEEKLY = 'weekly';
    case DAILY = 'daily';
    case OTHER = 'other';
}
