<?php

namespace App\Filament\Personal\Resources\FitnessTests\Pages;

use App\Filament\Personal\Resources\FitnessTests\FitnessTestResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateFitnessTest extends CreateRecord
{
    protected static string $resource = FitnessTestResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $authId = Auth::id();

        $data['user_id'] = $authId;
        $data['input_by_id'] = $authId;

        return $data;
    }
}
