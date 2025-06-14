<?php

namespace App\Filament\Admin\Resources\OrganizationCommandResource\Pages;

use App\Filament\Admin\Resources\OrganizationCommandResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrganizationCommands extends ListRecords
{
    protected static string $resource = OrganizationCommandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
