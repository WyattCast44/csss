<?php

namespace App\Filament\Admin\Resources\RankResource\Pages;

use App\Filament\Admin\Resources\RankResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRanks extends ListRecords
{
    protected static string $resource = RankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
