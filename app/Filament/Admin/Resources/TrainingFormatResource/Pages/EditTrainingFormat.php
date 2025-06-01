<?php

namespace App\Filament\Admin\Resources\TrainingFormatResource\Pages;

use App\Filament\Admin\Resources\TrainingFormatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingFormat extends EditRecord
{
    protected static string $resource = TrainingFormatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
