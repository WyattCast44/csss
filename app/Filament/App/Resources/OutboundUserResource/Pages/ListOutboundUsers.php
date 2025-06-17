<?php

namespace App\Filament\App\Resources\OutboundUserResource\Pages;

use App\Filament\App\Resources\OutboundUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOutboundUsers extends ListRecords
{
    protected static string $resource = OutboundUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
