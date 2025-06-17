<?php

namespace App\Filament\App\Resources\AttachedUserResource\Pages;

use App\Filament\App\Resources\AttachedUserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditAttachedUser extends EditRecord
{
    protected static string $resource = AttachedUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
