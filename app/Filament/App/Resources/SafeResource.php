<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SafeResource\Pages;
use App\Models\Safe;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SafeResource extends Resource
{
    protected static ?string $model = Safe::class;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('description')
                    ->maxLength(255),
                Select::make('building_id')
                    ->relationship('building', 'name'),
                Select::make('room_id')
                    ->relationship('room', 'name'),
                TextInput::make('number_drawers')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('number_of_locks')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('grade')
                    ->maxLength(255),
                TextInput::make('drawers'),
                Toggle::make('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('building.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('room.number')
                    ->label('Room #')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('room.name')
                    ->label('Room Name')
                    ->sortable(),
                TextColumn::make('building.base.abbr')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('number_drawers')
                    ->label('# Drawers')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('number_of_locks')
                    ->label('# Locks')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('grade')
                    ->searchable(),
                IconColumn::make('active')
                    ->boolean(),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSafes::route('/'),
            'create' => Pages\CreateSafe::route('/create'),
            'edit' => Pages\EditSafe::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
