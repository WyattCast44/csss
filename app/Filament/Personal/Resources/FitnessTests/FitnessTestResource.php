<?php

namespace App\Filament\Personal\Resources\FitnessTests;

use App\Filament\Personal\Resources\FitnessTests\Pages\CreateFitnessTest;
use App\Filament\Personal\Resources\FitnessTests\Pages\EditFitnessTest;
use App\Filament\Personal\Resources\FitnessTests\Pages\ListFitnessTests;
use App\Filament\Personal\Resources\FitnessTests\Pages\ViewFitnessTest;
use App\Filament\Personal\Resources\FitnessTests\Schemas\FitnessTestForm;
use App\Filament\Personal\Resources\FitnessTests\Schemas\FitnessTestInfolist;
use App\Filament\Personal\Resources\FitnessTests\Tables\FitnessTestsTable;
use App\Models\FitnessTest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FitnessTestResource extends Resource
{
    protected static ?string $model = FitnessTest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    public static function form(Schema $schema): Schema
    {
        return FitnessTestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FitnessTestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FitnessTestsTable::configure($table);
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
            'index' => ListFitnessTests::route('/'),
            'create' => CreateFitnessTest::route('/create'),
            'view' => ViewFitnessTest::route('/{record}'),
            'edit' => EditFitnessTest::route('/{record}/edit'),
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
