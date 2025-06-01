<?php

namespace App\Filament\Admin\Resources\GlobalTrainingResource\Pages;

use App\Filament\Admin\Resources\GlobalTrainingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGlobalTrainings extends ListRecords
{
    protected static string $resource = GlobalTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
