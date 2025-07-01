<?php

namespace App\Filament\Admin\Resources\TrainingFormatResource;

use App\Filament\Admin\Resources\TrainingFormatResource\Pages\CreateTrainingFormat;
use App\Filament\Admin\Resources\TrainingFormatResource\Pages\EditTrainingFormat;
use App\Filament\Admin\Resources\TrainingFormatResource\Pages\ListTrainingFormats;
use App\Filament\Admin\Resources\TrainingFormatResource\Schemas\TrainingFormatForm;
use App\Filament\Admin\Resources\TrainingFormatResource\Tables\TrainingFormatsTable;
use App\Models\TrainingFormat;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrainingFormatResource extends Resource
{
    protected static ?string $model = TrainingFormat::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Training';

    public static function form(Schema $schema): Schema
    {
        return TrainingFormatForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrainingFormatsTable::configure($table);
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
            'index' => ListTrainingFormats::route('/'),
            'create' => CreateTrainingFormat::route('/create'),
            'edit' => EditTrainingFormat::route('/{record}/edit'),
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