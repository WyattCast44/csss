<?php

namespace App\Filament\App\Resources\EntryAccessListResource\Pages;

use App\Filament\App\Resources\EntryAccessListResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEntryAccessLists extends ListRecords
{
    protected static string $resource = EntryAccessListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
} 