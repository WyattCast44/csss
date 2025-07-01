<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\BuildingResource\RelationManagers\RoomsRelationManager;
use App\Filament\App\Resources\RoomResource\Pages\CreateRoom;
use App\Filament\App\Resources\RoomResource\Pages\EditRoom;
use App\Filament\App\Resources\RoomResource\Pages\ListRooms;
use App\Models\Room;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static string|\UnitEnum|null $navigationGroup = 'Infrastructure';

    protected static ?string $navigationParentItem = 'Buildings';

    public static function getGloballySearchableAttributes(): array
    {
        return ['number', 'name', 'building.name'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return 'Room #' . $record->number . ' - ' . $record->name;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('building_id')
                    ->relationship('building', 'name'),
                TextInput::make('number')
                    ->label('Room #')
                    ->required()
                    ->maxLength(255),
                TextInput::make('name')
                    ->label('Room Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('description')
                    ->maxLength(255),
                Toggle::make('active')
                    ->required(),
                Toggle::make('has_eal')
                    ->required(),
                Toggle::make('has_safes')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('Room #')
                    ->searchable(),
                TextColumn::make('building.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Room Name')
                    ->searchable(),
                TextColumn::make('building.base.abbr')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('has_eal')
                    ->label('Has EAL')
                    ->boolean(),
                IconColumn::make('has_safes')
                    ->label('Has Safes')
                    ->boolean(),
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
            RoomsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRooms::route('/'),
            'create' => CreateRoom::route('/create'),
            'edit' => EditRoom::route('/{record}/edit'),
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
