<?php

namespace App\Filament\Admin\Resources\OrganizationCommandResource\Pages;

use App\Filament\Admin\Resources\OrganizationCommandResource\OrganizationCommandResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditOrganizationCommand extends EditRecord
{
    protected static string $resource = OrganizationCommandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
