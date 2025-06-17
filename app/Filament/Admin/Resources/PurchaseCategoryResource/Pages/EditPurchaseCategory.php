<?php

namespace App\Filament\Admin\Resources\PurchaseCategoryResource\Pages;

use App\Filament\Admin\Resources\PurchaseCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseCategory extends EditRecord
{
    protected static string $resource = PurchaseCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
