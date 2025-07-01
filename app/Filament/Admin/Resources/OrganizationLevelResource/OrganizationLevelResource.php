<?php

namespace App\Filament\Admin\Resources\OrganizationLevelResource;

use App\Filament\Admin\Resources\OrganizationLevelResource\Pages\CreateOrganizationLevel;
use App\Filament\Admin\Resources\OrganizationLevelResource\Pages\EditOrganizationLevel;
use App\Filament\Admin\Resources\OrganizationLevelResource\Pages\ListOrganizationLevels;
use App\Filament\Admin\Resources\OrganizationLevelResource\Schemas\OrganizationLevelForm;
use App\Filament\Admin\Resources\OrganizationLevelResource\Tables\OrganizationLevelsTable;
use App\Models\OrganizationLevel;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrganizationLevelResource extends Resource
{
    protected static ?string $model = OrganizationLevel::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|\UnitEnum|null $navigationGroup = 'Metadata';

    public static function form(Schema $schema): Schema
    {
        return OrganizationLevelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrganizationLevelsTable::configure($table);
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
            'index' => ListOrganizationLevels::route('/'),
            'create' => CreateOrganizationLevel::route('/create'),
            'edit' => EditOrganizationLevel::route('/{record}/edit'),
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