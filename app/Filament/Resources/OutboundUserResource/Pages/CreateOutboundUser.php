<?php

namespace App\Filament\Resources\OutboundUserResource\Pages;

use App\Filament\Resources\OutboundUserResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateOutboundUser extends CreateRecord
{
    protected static string $resource = OutboundUserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['organization_id'] = Filament::getTenant()->id;

        return $data;
    }
}
