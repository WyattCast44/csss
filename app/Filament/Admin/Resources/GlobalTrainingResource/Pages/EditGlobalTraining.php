<?php

namespace App\Filament\Admin\Resources\GlobalTrainingResource\Pages;

use App\Filament\Admin\Resources\GlobalTrainingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGlobalTraining extends EditRecord
{
    protected static string $resource = GlobalTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
