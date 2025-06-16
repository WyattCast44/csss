<?php

namespace App\Filament\App\Resources\BuildingResource\Pages;

use App\Filament\App\Resources\BuildingResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateBuilding extends CreateRecord
{
    protected static string $resource = BuildingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['organization_id'] = Filament::getTenant()->id;

        return $data;
    }
}
