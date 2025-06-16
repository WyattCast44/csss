<?php

namespace App\Filament\Admin\Resources\OrganizationResource\Pages;

use App\Filament\Admin\Resources\OrganizationResource;
use App\Filament\Admin\Resources\OrganizationResource\RelationManagers\ChildrenRelationManager;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewOrganization extends ViewRecord
{
    protected static string $resource = OrganizationResource::class;

    public function infolist(Infolist $infolist): Infolist
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
            Actions\EditAction::make(),
        ];
    }
}
