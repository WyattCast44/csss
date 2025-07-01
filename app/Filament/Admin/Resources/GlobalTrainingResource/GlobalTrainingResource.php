<?php

namespace App\Filament\Admin\Resources\GlobalTrainingResource;

use App\Filament\Admin\Resources\GlobalTrainingResource\Pages\CreateGlobalTraining;
use App\Filament\Admin\Resources\GlobalTrainingResource\Pages\EditGlobalTraining;
use App\Filament\Admin\Resources\GlobalTrainingResource\Pages\ListGlobalTrainings;
use App\Filament\Admin\Resources\GlobalTrainingResource\Schemas\GlobalTrainingForm;
use App\Filament\Admin\Resources\GlobalTrainingResource\Tables\GlobalTrainingsTable;
use App\Models\GlobalTraining;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GlobalTrainingResource extends Resource
{
    protected static ?string $model = GlobalTraining::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|\UnitEnum|null $navigationGroup = 'Training';

    public static function form(Schema $schema): Schema
    {
        return GlobalTrainingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GlobalTrainingsTable::configure($table);
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
            'index' => ListGlobalTrainings::route('/'),
            'create' => CreateGlobalTraining::route('/create'),
            'edit' => EditGlobalTraining::route('/{record}/edit'),
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
