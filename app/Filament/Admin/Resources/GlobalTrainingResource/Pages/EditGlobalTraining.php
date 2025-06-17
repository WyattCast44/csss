<?php

namespace App\Filament\Admin\Resources\GlobalTrainingResource\Pages;

use App\Filament\Admin\Resources\GlobalTrainingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditGlobalTraining extends EditRecord
{
    protected static string $resource = GlobalTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
