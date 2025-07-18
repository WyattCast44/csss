<?php

namespace App\Filament\App\Resources\InboundUserResource\Pages;

use App\Filament\App\Resources\InboundUserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInboundUser extends ViewRecord
{
    protected static string $resource = InboundUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
