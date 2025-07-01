<?php

namespace App\Filament\Admin\Resources\BranchResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                TextInput::make('short_name')
                    ->maxLength(100),
                TextInput::make('abbr')
                    ->required()
                    ->maxLength(10),
            ]);
    }
}
