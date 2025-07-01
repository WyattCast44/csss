<?php

namespace App\Filament\Admin\Resources\TrainingFormatResource\Pages;

use App\Filament\Admin\Resources\TrainingFormatResource\TrainingFormatResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditTrainingFormat extends EditRecord
{
    protected static string $resource = TrainingFormatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
