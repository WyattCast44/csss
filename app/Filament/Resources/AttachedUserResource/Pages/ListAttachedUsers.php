<?php

namespace App\Filament\Resources\AttachedUserResource\Pages;

use App\Filament\Resources\AttachedUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttachedUsers extends ListRecords
{
    protected static string $resource = AttachedUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
