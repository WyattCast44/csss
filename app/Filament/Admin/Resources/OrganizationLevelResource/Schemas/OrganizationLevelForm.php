<?php

namespace App\Filament\Admin\Resources\OrganizationLevelResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrganizationLevelForm
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
                    ->maxLength(255),
                Select::make('branch_id')
                    ->relationship('branch', 'name'),
            ]);
    }
} 