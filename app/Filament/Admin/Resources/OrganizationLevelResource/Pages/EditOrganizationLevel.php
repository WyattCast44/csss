<?php

namespace App\Filament\Admin\Resources\OrganizationLevelResource\Pages;

use App\Filament\Admin\Resources\OrganizationLevelResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditOrganizationLevel extends EditRecord
{
    protected static string $resource = OrganizationLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
