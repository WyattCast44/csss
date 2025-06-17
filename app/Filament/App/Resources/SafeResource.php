<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SafeResource\Pages\CreateSafe;
use App\Filament\App\Resources\SafeResource\Pages\EditSafe;
use App\Filament\App\Resources\SafeResource\Pages\ListSafes;
use App\Models\Safe;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SafeResource extends Resource
{
    protected static ?string $model = Safe::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-lock-closed';

    protected static string|\UnitEnum|null $navigationGroup = 'Infrastructure';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                Repeater::make('drawers')
                    ->label('Drawers')
                    ->collapsible()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Drawer Name')
                            ->helperText('For example: SEC-01, SEC-02, etc.')
                            ->required(),
                        TextInput::make('number')
                            ->label('Drawer #')
                            ->helperText('For example: 1, 2, 3, etc.')
                            ->required(),
                    ])
                    ->columns(2),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSafes::route('/'),
            'create' => CreateSafe::route('/create'),
            'edit' => EditSafe::route('/{record}/edit'),
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
