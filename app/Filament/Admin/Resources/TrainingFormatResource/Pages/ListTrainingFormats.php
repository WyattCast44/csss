<?php

namespace App\Filament\Admin\Resources\TrainingFormatResource\Pages;

use App\Filament\Admin\Resources\TrainingFormatResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingFormats extends ListRecords
{
    use HasResizableColumn;

    protected static string $resource = TrainingFormatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
