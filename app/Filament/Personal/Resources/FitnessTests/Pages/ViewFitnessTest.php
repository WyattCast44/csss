<?php

namespace App\Filament\Personal\Resources\FitnessTests\Pages;

use App\Filament\Personal\Resources\FitnessTests\FitnessTestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFitnessTest extends ViewRecord
{
    protected static string $resource = FitnessTestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
