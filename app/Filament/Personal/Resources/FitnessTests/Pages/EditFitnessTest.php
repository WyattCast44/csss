<?php

namespace App\Filament\Personal\Resources\FitnessTests\Pages;

use App\Filament\Personal\Resources\FitnessTests\FitnessTestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFitnessTest extends EditRecord
{
    protected static string $resource = FitnessTestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
