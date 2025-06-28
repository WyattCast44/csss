<?php

namespace App\Filament\Admin\Resources\CommissionSources\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CommissionSourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('abbr')
                    ->required()
                    ->maxLength(10),
            ]);
    }
}
