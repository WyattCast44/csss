<?php

namespace App\Filament\Admin\Resources\RankResource\Pages;

use App\Filament\Admin\Resources\RankResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRanks extends ListRecords
{
    use HasResizableColumn;

    protected static string $resource = RankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
