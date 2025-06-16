<?php

namespace App\Filament\Admin\Resources\OrganizationResource\RelationManagers;

use App\Filament\Admin\Resources\OrganizationResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class ChildrenRelationManager extends RelationManager
{
    protected static string $relationship = 'children';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return OrganizationResource::form($form);
    }

    public function table(Table $table): Table
    {
        return OrganizationResource::table($table);
    }
}
