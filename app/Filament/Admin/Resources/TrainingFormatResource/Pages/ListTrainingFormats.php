<?php

namespace App\Filament\Admin\Resources\TrainingFormatResource\Pages;

use App\Filament\Admin\Resources\TrainingFormatResource\TrainingFormatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTrainingFormats extends ListRecords
{
    protected static string $resource = TrainingFormatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
