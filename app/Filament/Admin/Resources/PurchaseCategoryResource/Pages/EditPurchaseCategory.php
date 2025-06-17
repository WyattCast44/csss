<?php

namespace App\Filament\Admin\Resources\PurchaseCategoryResource\Pages;

use App\Filament\Admin\Resources\PurchaseCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseCategory extends EditRecord
{
    protected static string $resource = PurchaseCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
