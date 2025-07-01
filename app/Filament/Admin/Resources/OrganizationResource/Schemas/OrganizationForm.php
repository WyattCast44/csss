<?php

namespace App\Filament\Admin\Resources\OrganizationResource\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class OrganizationForm
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
                Textarea::make('description')
                    ->columnSpanFull(),
                Repeater::make('pas_codes')
                    ->addActionLabel('Add PAS Code')
                    ->schema([
                        TextInput::make('label')->required(),
                        TextInput::make('code')->required(),
                    ])
                    ->collapsible(),
                Repeater::make('mailing_addresses')
                    ->addActionLabel('Add mailing address')
                    ->columns(2)
                    ->schema([
                        TextInput::make('label')->required(),
                        TextInput::make('address')->required(),
                    ])
                    ->collapsible()
                    ->reorderableWithButtons(),
                Repeater::make('physical_addresses')
                    ->addActionLabel('Add physical address')
                    ->columns(2)
                    ->schema([
                        TextInput::make('label')->required(),
                        TextInput::make('address')->required(),
                    ])
                    ->collapsible()
                    ->reorderableWithButtons(),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Repeater::make('phone_numbers')
                    ->addActionLabel('Add phone number')
                    ->columns(2)
                    ->schema([
                        TextInput::make('label')->required(),
                        TextInput::make('phone_number')->required(),
                    ])
                    ->collapsible()
                    ->reorderableWithButtons(),
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
                Select::make('level_id')
                    ->relationship('level', 'name')
                    ->nullable(),
                Select::make('command_id')
                    ->relationship('command', 'name')
                    ->nullable(),
                Select::make('parent_id')
                    ->relationship('parent', 'name', modifyQueryUsing: fn (Builder $query) => $query->shared()->approved(), ignoreRecord: true)
                    ->nullable()
                    ->searchable()
                    ->preload(),
                TextInput::make('avatar')
                    ->maxLength(255),
                Toggle::make('approved')
                    ->required(),
            ]);
    }
}
