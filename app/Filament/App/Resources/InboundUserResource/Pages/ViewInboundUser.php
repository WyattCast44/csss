<?php

namespace App\Filament\App\Resources\InboundUserResource\Pages;

use App\Filament\App\Resources\InboundUserResource;
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
