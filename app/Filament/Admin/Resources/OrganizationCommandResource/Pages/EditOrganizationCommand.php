<?php

namespace App\Filament\Admin\Resources\OrganizationCommandResource\Pages;

use App\Filament\Admin\Resources\OrganizationCommandResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrganizationCommand extends EditRecord
{
    protected static string $resource = OrganizationCommandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
