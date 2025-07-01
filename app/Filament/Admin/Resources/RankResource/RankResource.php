<?php

namespace App\Filament\Admin\Resources\RankResource;

use App\Filament\Admin\Resources\RankResource\Pages\CreateRank;
use App\Filament\Admin\Resources\RankResource\Pages\EditRank;
use App\Filament\Admin\Resources\RankResource\Pages\ListRanks;
use App\Filament\Admin\Resources\RankResource\Schemas\RankForm;
use App\Filament\Admin\Resources\RankResource\Tables\RanksTable;
use App\Models\Rank;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RankResource extends Resource
{
    protected static ?string $model = Rank::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static string|\UnitEnum|null $navigationGroup = 'Metadata';

    public static function form(Schema $schema): Schema
    {
        return RankForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RanksTable::configure($table);
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
            'index' => ListRanks::route('/'),
            'create' => CreateRank::route('/create'),
            'edit' => EditRank::route('/{record}/edit'),
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
