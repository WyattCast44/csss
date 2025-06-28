<?php

namespace App\Filament\Admin\Resources\CommissionSources;

use App\Filament\Admin\Resources\CommissionSources\Pages\CreateCommissionSource;
use App\Filament\Admin\Resources\CommissionSources\Pages\EditCommissionSource;
use App\Filament\Admin\Resources\CommissionSources\Pages\ListCommissionSources;
use App\Filament\Admin\Resources\CommissionSources\Schemas\CommissionSourceForm;
use App\Filament\Admin\Resources\CommissionSources\Tables\CommissionSourcesTable;
use BackedEnum;
use App\Models\CommissionSource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CommissionSourceResource extends Resource
{
    protected static ?string $model = CommissionSource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|\UnitEnum|null $navigationGroup = 'Metadata';

    public static function form(Schema $schema): Schema
    {
        return CommissionSourceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CommissionSourcesTable::configure($table);
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
            'index' => ListCommissionSources::route('/'),
            'create' => CreateCommissionSource::route('/create'),
            'edit' => EditCommissionSource::route('/{record}/edit'),
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
