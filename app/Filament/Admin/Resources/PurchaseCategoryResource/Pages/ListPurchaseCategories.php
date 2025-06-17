<?php

namespace App\Filament\Admin\Resources\PurchaseCategoryResource\Pages;

use App\Filament\Admin\Resources\PurchaseCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPurchaseCategories extends ListRecords
{
    protected static string $resource = PurchaseCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
