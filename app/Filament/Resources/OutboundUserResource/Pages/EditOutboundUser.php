<?php

namespace App\Filament\Resources\OutboundUserResource\Pages;

use App\Filament\Resources\OutboundUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOutboundUser extends EditRecord
{
    protected static string $resource = OutboundUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
