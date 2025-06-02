<?php

namespace App\Filament\Resources\InboundUserResource\Pages;

use App\Filament\Resources\InboundUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInboundUser extends ViewRecord
{
    protected static string $resource = InboundUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
