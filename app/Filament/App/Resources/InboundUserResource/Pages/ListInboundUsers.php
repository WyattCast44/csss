<?php

namespace App\Filament\App\Resources\InboundUserResource\Pages;

use App\Filament\App\Resources\InboundUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInboundUsers extends ListRecords
{
    protected static string $resource = InboundUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
