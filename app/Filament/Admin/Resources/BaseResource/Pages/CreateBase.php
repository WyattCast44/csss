<?php

namespace App\Filament\Admin\Resources\BaseResource\Pages;

use App\Filament\Admin\Resources\BaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBase extends CreateRecord
{
    protected static string $resource = BaseResource::class;
}
