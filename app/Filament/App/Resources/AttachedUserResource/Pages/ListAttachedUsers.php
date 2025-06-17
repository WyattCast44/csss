<?php

namespace App\Filament\App\Resources\AttachedUserResource\Pages;

use App\Filament\App\Resources\AttachedUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttachedUsers extends ListRecords
{
    protected static string $resource = AttachedUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
