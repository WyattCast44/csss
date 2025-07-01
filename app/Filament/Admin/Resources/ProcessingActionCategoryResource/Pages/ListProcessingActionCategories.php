<?php

namespace App\Filament\Admin\Resources\ProcessingActionCategoryResource\Pages;

use App\Filament\Admin\Resources\ProcessingActionCategoryResource\ProcessingActionCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProcessingActionCategories extends ListRecords
{
    protected static string $resource = ProcessingActionCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
