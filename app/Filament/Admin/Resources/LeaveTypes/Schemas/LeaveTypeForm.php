<?php

namespace App\Filament\Admin\Resources\LeaveTypes\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LeaveTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Toggle::make('active')
                    ->required()
                    ->default(true)
                    ->inline(false),
                Textarea::make('description')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
