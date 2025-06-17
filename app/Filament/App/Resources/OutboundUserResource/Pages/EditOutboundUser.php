<?php

namespace App\Filament\App\Resources\OutboundUserResource\Pages;

use App\Filament\App\Resources\OutboundUserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditOutboundUser extends EditRecord
{
    protected static string $resource = OutboundUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
