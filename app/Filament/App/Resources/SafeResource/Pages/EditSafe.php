<?php

namespace App\Filament\App\Resources\SafeResource\Pages;

use App\Filament\App\Resources\SafeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSafe extends EditRecord
{
    protected static string $resource = SafeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
