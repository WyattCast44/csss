<?php

namespace App\Filament\Admin\Resources\OrganizationLevelResource\Pages;

use App\Filament\Admin\Resources\OrganizationLevelResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrganizationLevels extends ListRecords
{
    use HasResizableColumn;

    protected static string $resource = OrganizationLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
