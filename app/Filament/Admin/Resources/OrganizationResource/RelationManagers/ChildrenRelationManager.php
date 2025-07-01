<?php

namespace App\Filament\Admin\Resources\OrganizationResource\RelationManagers;

use App\Filament\Admin\Resources\OrganizationResource\OrganizationResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ChildrenRelationManager extends RelationManager
{
    protected static string $relationship = 'children';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return OrganizationResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return OrganizationResource::table($table);
    }
}
