<?php

namespace App\Filament\Resources\InboundUserResource\Pages;

use App\Filament\Resources\InboundUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInboundUsers extends ListRecords
{
    protected static string $resource = InboundUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
