<?php

namespace App\Filament\Admin\Resources\BaseResource\Pages;

use App\Filament\Admin\Resources\BaseResource\BaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBases extends ListRecords
{
    protected static string $resource = BaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
