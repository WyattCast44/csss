<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Organization;
use App\Rules\AllowedEmailDomain;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class EditOrganizationPage extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Manage your organization';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                FileUpload::make('avatar')
                    ->label('Organization Logo')
                    ->image()
                    ->avatar()
                    ->imageEditor()
                    ->circleCropper()
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->label('Organization Name')
                    ->required(),
                TextInput::make('abbr')
                    ->label('Organization Abbreviation')
                    ->required()
                    ->live(),
                TextInput::make('email')
                    ->label('Organization Email')
                    ->email()
                    ->required()
                    ->helperText('This email will be used to send notifications to the organization. You must use an official DoD email address.')
                    ->rules([
                        new AllowedEmailDomain,
                    ]),
                Repeater::make('pas_codes')
                    ->addActionLabel('Add PAS Code')
                    ->columns(1)
                    ->schema([
                        TextInput::make('label')->required(),
                        TextInput::make('code')->required(),
                    ])
                    ->collapsible(),
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
                Select::make('level_id')
                    ->label('Organization Level')
                    ->relationship('level', 'name')
                    ->required(),
                Select::make('parent_id')
                    ->label('Parent Organization')
                    ->relationship('parent', 'name', modifyQueryUsing: function (Builder $query, Organization $record) {
                        return $query
                            ->where('id', '!=', $record->id)
                            ->where('personal', false);
                    })
                    ->nullable()
                    ->searchable()
                    ->preload(),
            ]);
    }
}
