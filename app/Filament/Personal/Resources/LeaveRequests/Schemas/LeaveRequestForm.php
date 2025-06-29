<?php

namespace App\Filament\Personal\Resources\LeaveRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Schema;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type_id')
                    ->relationship('type', 'name')
                    ->required(),
                TextInput::make('notes')
                    ->nullable(),
                FusedGroup::make([
                    DatePicker::make('start_date')
                        ->required()
                        ->default(now()->addDays(1))
                        ->minDate(now()->subDays(30))
                        ->maxDate(now()->addMonths(12)),
                    TimePicker::make('start_time')
                        ->required()
                        ->default('08:00')
                        ->seconds(false),
                ])->label('Leave Start'),
                FusedGroup::make([
                    DatePicker::make('end_date')
                        ->required()
                        ->default(now()->addDays(2))
                        ->minDate(now()->subDays(30))
                        ->maxDate(now()->addMonths(12)),
                    TimePicker::make('end_time')
                        ->required()
                        ->default('17:00')
                        ->seconds(false),
                ])->label('Leave End'),
                Toggle::make('requires_approval')
                    ->required()
                    ->default(true)
                    ->inline(false),
                Select::make('route_to')
                    ->relationship('routeTo', 'display_name')
                    ->nullable()
                    ->searchable()
                    ->preload(),
            ]);
    }
}
