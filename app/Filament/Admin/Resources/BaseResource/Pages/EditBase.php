<?php

namespace App\Filament\Admin\Resources\BaseResource\Pages;

use App\Filament\Admin\Resources\BaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBase extends EditRecord
{
    protected static string $resource = BaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
