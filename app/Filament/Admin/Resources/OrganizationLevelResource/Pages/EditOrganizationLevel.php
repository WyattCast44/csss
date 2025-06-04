<?php

namespace App\Filament\Admin\Resources\OrganizationLevelResource\Pages;

use App\Filament\Admin\Resources\OrganizationLevelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrganizationLevel extends EditRecord
{
    protected static string $resource = OrganizationLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
