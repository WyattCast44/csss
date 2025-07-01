<?php

namespace App\Filament\Admin\Resources\BaseResource;

use App\Filament\Admin\Resources\BaseResource\Pages\CreateBase;
use App\Filament\Admin\Resources\BaseResource\Pages\EditBase;
use App\Filament\Admin\Resources\BaseResource\Pages\ListBases;
use App\Filament\Admin\Resources\BaseResource\Schemas\BaseForm;
use App\Filament\Admin\Resources\BaseResource\Tables\BasesTable;
use App\Models\Base;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BaseResource extends Resource
{
    protected static ?string $model = Base::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static string|\UnitEnum|null $navigationGroup = 'Metadata';

    public static function form(Schema $schema): Schema
    {
        return BaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BasesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBases::route('/'),
            'create' => CreateBase::route('/create'),
            'edit' => EditBase::route('/{record}/edit'),
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
