<?php

namespace App\Filament\Admin\Resources\ProcessingActionCategoryResource\Pages;

use App\Filament\Admin\Resources\ProcessingActionCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditProcessingActionCategory extends EditRecord
{
    protected static string $resource = ProcessingActionCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
