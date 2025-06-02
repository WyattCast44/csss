<?php

namespace App\Filament\Resources\InboundUserResource\Pages;

use App\Filament\Resources\InboundUserResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateInboundUser extends CreateRecord
{
    protected static string $resource = InboundUserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['organization_id'] = Filament::getTenant()->id;

        return $data;
    }
}
