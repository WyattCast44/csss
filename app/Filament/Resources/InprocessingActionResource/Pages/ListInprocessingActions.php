<?php

namespace App\Filament\Resources\InprocessingActionResource\Pages;

use App\Filament\Resources\InprocessingActionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInprocessingActions extends ListRecords
{
    protected static string $resource = InprocessingActionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
