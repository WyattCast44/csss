<?php

namespace App\Filament\App\Resources\InprocessingActionResource\Pages;

use App\Filament\App\Resources\InprocessingActionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditInprocessingAction extends EditRecord
{
    protected static string $resource = InprocessingActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
