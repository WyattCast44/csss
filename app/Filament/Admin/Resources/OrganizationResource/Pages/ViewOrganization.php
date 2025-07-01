<?php

namespace App\Filament\Admin\Resources\OrganizationResource\Pages;

use App\Filament\Admin\Resources\OrganizationResource\OrganizationResource;
use App\Filament\Admin\Resources\OrganizationResource\RelationManagers\ChildrenRelationManager;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewOrganization extends ViewRecord
{
    protected static string $resource = OrganizationResource::class;

    public function infolist(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                Section::make('Organization Information')
                    ->collapsible()
                    ->columns(3)
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('abbr'),
                        TextEntry::make('branch.abbr')
                            ->label('Branch')
                            ->default('Not set'),
                        TextEntry::make('level.name')
                            ->label('Level')
                            ->default('Not set'),
                        TextEntry::make('command.abbr')
                            ->label('Command')
                            ->default('Not set'),
                        TextEntry::make('parent.name')
                            ->label('Parent')
                            ->default('Not set'),
                    ])->columnSpanFull(),
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
