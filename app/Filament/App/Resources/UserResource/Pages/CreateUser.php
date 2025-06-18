<?php

namespace App\Filament\App\Resources\UserResource\Pages;

use App\Filament\App\Resources\UserResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        /** @var \App\Models\Organization $organization */
        $organization = Filament::getTenant();

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var \App\Models\Organization $organization */
        $organization = Filament::getTenant();

        $organization->users()->create($this->form->getState());
    }
}
