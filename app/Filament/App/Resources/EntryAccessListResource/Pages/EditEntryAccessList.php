<?php

namespace App\Filament\App\Resources\EntryAccessListResource\Pages;

use App\Filament\App\Resources\EntryAccessListResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditEntryAccessList extends EditRecord
{
    protected static string $resource = EntryAccessListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
} 