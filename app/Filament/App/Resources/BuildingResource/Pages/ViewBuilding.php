<?php

namespace App\Filament\App\Resources\BuildingResource\Pages;

use App\Filament\App\Resources\BuildingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBuilding extends ViewRecord
{
    protected static string $resource = BuildingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
