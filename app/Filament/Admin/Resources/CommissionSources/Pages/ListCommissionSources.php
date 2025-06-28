<?php

namespace App\Filament\Admin\Resources\CommissionSources\Pages;

use App\Filament\Admin\Resources\CommissionSources\CommissionSourceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCommissionSources extends ListRecords
{
    protected static string $resource = CommissionSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
