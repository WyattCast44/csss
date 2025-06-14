<?php

namespace App\Filament\Resources\AttachedUserResource\Pages;

use App\Filament\Resources\AttachedUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttachedUser extends EditRecord
{
    protected static string $resource = AttachedUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
