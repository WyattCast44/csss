<?php

namespace App\Filament\Admin\Resources\BranchResource;

use App\Filament\Admin\Resources\BranchResource\Pages\CreateBranch;
use App\Filament\Admin\Resources\BranchResource\Pages\EditBranch;
use App\Filament\Admin\Resources\BranchResource\Pages\ListBranches;
use App\Filament\Admin\Resources\BranchResource\Schemas\BranchForm;
use App\Filament\Admin\Resources\BranchResource\Tables\BranchesTable;
use App\Models\Branch;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-scale';

    protected static string|\UnitEnum|null $navigationGroup = 'Metadata';

    public static function form(Schema $schema): Schema
    {
        return BranchForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BranchesTable::configure($table);
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
            'index' => ListBranches::route('/'),
            'create' => CreateBranch::route('/create'),
            'edit' => EditBranch::route('/{record}/edit'),
        ];
    }
}
