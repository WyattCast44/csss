<?php

namespace App\Filament\Admin\Resources\ProcessingActionCategoryResource\Pages;

use App\Filament\Admin\Resources\ProcessingActionCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcessingActionCategory extends EditRecord
{
    protected static string $resource = ProcessingActionCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
