<?php

namespace App\Filament\Admin\Resources\OrganizationResource\Pages;

use App\Filament\Admin\Resources\OrganizationResource;
use App\Filament\Admin\Resources\OrganizationResource\RelationManagers\ChildrenRelationManager;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewOrganization extends ViewRecord
{
    protected static string $resource = OrganizationResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $infolist
            ->schema([
                Section::make('Organization Information')
                    ->collapsible()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('abbr'),
                        TextEntry::make('branch.abbr'),
                        TextEntry::make('level.name'),
                        TextEntry::make('command.abbr'),
                    ]),
            ]);
    }

    protected function getAllRelationManagers(): array
    {
        return [
            ChildrenRelationManager::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
