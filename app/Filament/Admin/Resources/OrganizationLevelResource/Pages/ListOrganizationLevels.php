<?php

namespace App\Filament\Admin\Resources\OrganizationLevelResource\Pages;

use App\Filament\Admin\Resources\OrganizationLevelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrganizationLevels extends ListRecords
{
    protected static string $resource = OrganizationLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
