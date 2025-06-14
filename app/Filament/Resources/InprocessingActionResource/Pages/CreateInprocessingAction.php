<?php

namespace App\Filament\Resources\InprocessingActionResource\Pages;

use App\Filament\Resources\InprocessingActionResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateInprocessingAction extends CreateRecord
{
    protected static string $resource = InprocessingActionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['organization_id'] = Filament::getTenant()->id;

        return $data;
    }
}
