<?php

namespace App\Filament\Admin\Resources\ProcessingActionCategoryResource;

use App\Filament\Admin\Resources\ProcessingActionCategoryResource\Pages\CreateProcessingActionCategory;
use App\Filament\Admin\Resources\ProcessingActionCategoryResource\Pages\EditProcessingActionCategory;
use App\Filament\Admin\Resources\ProcessingActionCategoryResource\Pages\ListProcessingActionCategories;
use App\Filament\Admin\Resources\ProcessingActionCategoryResource\Schemas\ProcessingActionCategoryForm;
use App\Filament\Admin\Resources\ProcessingActionCategoryResource\Tables\ProcessingActionCategoriesTable;
use App\Models\ProcessingActionCategory;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProcessingActionCategoryResource extends Resource
{
    protected static ?string $model = ProcessingActionCategory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Action Categories';

    protected static string|\UnitEnum|null $navigationGroup = 'Metadata';

    public static function form(Schema $schema): Schema
    {
        return ProcessingActionCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProcessingActionCategoriesTable::configure($table);
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
            'index' => ListProcessingActionCategories::route('/'),
            'create' => CreateProcessingActionCategory::route('/create'),
            'edit' => EditProcessingActionCategory::route('/{record}/edit'),
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
