<?php

namespace App\Filament\Personal\Resources\FitnessTests\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FitnessTestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Test Details')
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        DatePicker::make('date')
                            ->required()
                            ->helperText('The date the test was administered')
                            ->default(now()->format('Y-m-d')),
                        TextInput::make('score')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->helperText('The total score for the test')
                            ->suffix('%'),
                        Toggle::make('passed')
                            ->label('Passed')
                            ->required()
                            ->inline(false)
                            ->default(false)
                            ->helperText('Whether the test was passed'),
                        TextInput::make('test_location')
                            ->required()
                            ->helperText('Where the test was administered'),
                        DatePicker::make('next_test_date')
                            ->label('Next Due Date')
                            ->helperText('The date your request to retest, based on the test results')
                            ->required()
                            ->minDate(now()->addDays(1))
                            ->maxDate(now()->addYears(2))
                            ->hintActions([
                                Action::make('add6months')
                                    ->label('Add 6 Months')
                                    ->action(function (DatePicker $component) {
                                        $component->state(now()->addMonths(6)->endOfMonth()->format('Y-m-d'));
                                    })
                                    ->visible(
                                        function (string $operation) {
                                            return match ($operation) {
                                                'create' => true,
                                                'edit' => true,
                                                'view' => false,
                                                default => false,
                                            };
                                        }
                                    ),
                                Action::make('add1year')
                                    ->label('Add 1 Year')
                                    ->action(function (DatePicker $component) {
                                        $component->state(now()->addYears(1)->endOfMonth()->format('Y-m-d'));
                                    })
                                    ->visible(
                                        function (string $operation) {
                                            return match ($operation) {
                                                'create' => true,
                                                'edit' => true,
                                                'view' => false,
                                                default => false,
                                            };
                                        }
                                    ),
                            ]),
                        TextInput::make('notes')
                            ->nullable(),
                    ]),
                Section::make('Component Results')
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('results')
                            ->label('Component Results')
                            ->hiddenLabel()
                            ->columns(4)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Component')
                                    ->required()
                                    ->helperText('The component of the test'),
                                TextInput::make('value')
                                    ->label('Score')
                                    ->required()
                                    ->helperText('The score for the component'),
                                Toggle::make('passed')
                                    ->label('Passed')
                                    ->required()
                                    ->inline(false)
                                    ->default(true)
                                    ->helperText('Whether the component was passed'),
                                Toggle::make('exempt')
                                    ->label('Exempt')
                                    ->inline(false)
                                    ->default(false)
                                    ->required()
                                    ->helperText('Whether the component was exempt'),
                            ]),
                    ]),
                Section::make('Attachments')
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('attachments')
                            ->label('Attachments')
                            ->hiddenLabel()
                            ->multiple()
                            ->visibility('private')
                            ->previewable(false)
                            ->downloadable(true)
                            ->fetchFileInformation(false),
                    ]),
            ]);
    }
}
