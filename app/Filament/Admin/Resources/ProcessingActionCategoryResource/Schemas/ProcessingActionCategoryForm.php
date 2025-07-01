<?php

namespace App\Filament\Admin\Resources\ProcessingActionCategoryResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProcessingActionCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('organization_id')
                    ->relationship('organization', 'name')
                    ->preload()
                    ->searchable()
                    ->helperText('If no organization is selected, the category will be global.'),
            ]);
    }
} 