<?php

namespace App\Filament\Admin\Resources\OrganizationResource\Tables;

use App\Filament\Admin\Resources\OrganizationResource\RelationManagers\ChildrenRelationManager;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class OrganizationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Logo')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->circular()
                    ->checkFileExistence(false)
                    ->defaultImageUrl(url('images/dod.png')),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('abbr')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('branch.abbr')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('level.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('command.abbr')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('parent.abbr')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->hiddenOn(ChildrenRelationManager::class),
                IconColumn::make('approved')
                    ->boolean(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('branch_id')
                    ->label('Branch')
                    ->relationship('branch', 'name'),
                SelectFilter::make('level_id')
                    ->label('Org Level')
                    ->relationship('level', 'name'),
                SelectFilter::make('parent_id')
                    ->label('Parent')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload(),
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
            ])
            ->defaultSort('created_at', 'desc');
    }
} 