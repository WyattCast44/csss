<?php

namespace App\Filament\App\Resources\SafeResource\Pages;

use App\Filament\App\Resources\SafeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSafes extends ListRecords
{
    protected static string $resource = SafeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
