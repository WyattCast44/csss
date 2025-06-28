<?php

namespace App\Filament\Admin\Resources\CommissionSources\Pages;

use App\Filament\Admin\Resources\CommissionSources\CommissionSourceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCommissionSource extends EditRecord
{
    protected static string $resource = CommissionSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
