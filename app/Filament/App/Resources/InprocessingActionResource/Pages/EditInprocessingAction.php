<?php

namespace App\Filament\App\Resources\InprocessingActionResource\Pages;

use App\Filament\App\Resources\InprocessingActionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInprocessingAction extends EditRecord
{
    protected static string $resource = InprocessingActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
