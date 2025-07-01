<?php

namespace App\Filament\Admin\Resources\BaseResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('abbr')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('icao_code')
                    ->label('ICAO Code')
                    ->maxLength(10)
                    ->unique(ignoreRecord: true),
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
            ]);
    }
} 