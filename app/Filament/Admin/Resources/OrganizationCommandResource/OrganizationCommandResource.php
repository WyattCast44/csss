<?php

namespace App\Filament\Admin\Resources\OrganizationCommandResource;

use App\Filament\Admin\Resources\OrganizationCommandResource\Pages\CreateOrganizationCommand;
use App\Filament\Admin\Resources\OrganizationCommandResource\Pages\EditOrganizationCommand;
use App\Filament\Admin\Resources\OrganizationCommandResource\Pages\ListOrganizationCommands;
use App\Filament\Admin\Resources\OrganizationCommandResource\Schemas\OrganizationCommandForm;
use App\Filament\Admin\Resources\OrganizationCommandResource\Tables\OrganizationCommandsTable;
use App\Models\OrganizationCommand;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrganizationCommandResource extends Resource
{
    protected static ?string $model = OrganizationCommand::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|\UnitEnum|null $navigationGroup = 'Metadata';

    public static function form(Schema $schema): Schema
    {
        return OrganizationCommandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrganizationCommandsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrganizationCommands::route('/'),
            'create' => CreateOrganizationCommand::route('/create'),
            'edit' => EditOrganizationCommand::route('/{record}/edit'),
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