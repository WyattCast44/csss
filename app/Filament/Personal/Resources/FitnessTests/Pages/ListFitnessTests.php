<?php

namespace App\Filament\Personal\Resources\FitnessTests\Pages;

use App\Filament\Personal\Resources\FitnessTests\FitnessTestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFitnessTests extends ListRecords
{
    protected static string $resource = FitnessTestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
