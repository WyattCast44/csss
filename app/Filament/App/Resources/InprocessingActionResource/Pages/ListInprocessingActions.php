<?php

namespace App\Filament\App\Resources\InprocessingActionResource\Pages;

use App\Filament\App\Resources\InprocessingActionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInprocessingActions extends ListRecords
{
    protected static string $resource = InprocessingActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
