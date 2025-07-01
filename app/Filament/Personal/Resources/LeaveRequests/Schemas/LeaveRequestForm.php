<?php

namespace App\Filament\Personal\Resources\LeaveRequests\Schemas;

use App\Enums\LeaveStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\FusedGroup;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;

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
                FusedGroup::make([
                    Select::make('requires_approval')
                        ->required()
                        ->live()
                        ->boolean('Yes', 'No'),
                    Select::make('route_to')
                        ->disabled(fn (Get $get): bool => $get('requires_approval') === 'false')
                        ->relationship('routeTo', 'display_name')
                        ->nullable()
                        ->searchable()
                        ->preload()
                        ->columnSpan(2),
                ])->label('Approval Required')->columns(3),
                Select::make('status')
                    ->options(LeaveStatus::class)
                    ->required()
                    ->hiddenOn(Operation::Create),
            ]);
    }
}
