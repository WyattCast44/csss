<?php

namespace App\Filament\Admin\Resources\PurchaseCategoryResource;

use App\Filament\Admin\Resources\PurchaseCategoryResource\Pages\CreatePurchaseCategory;
use App\Filament\Admin\Resources\PurchaseCategoryResource\Pages\EditPurchaseCategory;
use App\Filament\Admin\Resources\PurchaseCategoryResource\Pages\ListPurchaseCategories;
use App\Filament\Admin\Resources\PurchaseCategoryResource\Schemas\PurchaseCategoryForm;
use App\Filament\Admin\Resources\PurchaseCategoryResource\Tables\PurchaseCategoriesTable;
use App\Models\PurchaseCategory;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchaseCategoryResource extends Resource
{
    protected static ?string $model = PurchaseCategory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static string|\UnitEnum|null $navigationGroup = 'Purchasing';

    public static function form(Schema $schema): Schema
    {
        return PurchaseCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchaseCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchaseCategories::route('/'),
            'create' => CreatePurchaseCategory::route('/create'),
            'edit' => EditPurchaseCategory::route('/{record}/edit'),
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