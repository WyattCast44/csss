<?php

namespace App\Filament\Personal\Resources\FitnessTests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class FitnessTestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->date('m/d/Y')
                    ->sortable(),
                TextColumn::make('score')
                    ->sortable()
                    ->suffix('%'),
                IconColumn::make('passed')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('inputter.display_name')
                    ->label('Input By')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->date('m/d/Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->date('m/d/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->date('m/d/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
