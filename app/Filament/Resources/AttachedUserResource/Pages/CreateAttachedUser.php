<?php

namespace App\Filament\Resources\AttachedUserResource\Pages;

use App\Filament\Resources\AttachedUserResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateAttachedUser extends CreateRecord
{
    protected static string $resource = AttachedUserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['organization_id'] = Filament::getTenant()->id;

        return $data;
    }
}
