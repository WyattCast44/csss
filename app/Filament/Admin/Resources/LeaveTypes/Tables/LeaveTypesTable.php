<?php

namespace App\Filament\Admin\Resources\LeaveTypes\Tables;

use App\Models\LeaveType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class LeaveTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(25)
                    ->searchable()
                    ->sortable()
                    ->tooltip(fn (LeaveType $record): string => $record->description),
                IconColumn::make('active')
                    ->boolean()
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
