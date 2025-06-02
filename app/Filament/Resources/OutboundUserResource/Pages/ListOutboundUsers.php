<?php

namespace App\Filament\Resources\OutboundUserResource\Pages;

use App\Filament\Resources\OutboundUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOutboundUsers extends ListRecords
{
    protected static string $resource = OutboundUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
