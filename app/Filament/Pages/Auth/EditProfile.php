<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class EditProfile extends \Filament\Auth\Pages\EditProfile
{
    protected function getAvatarFormComponent(): Component
    {
        return FileUpload::make('avatar')
            ->label('Avatar')
            ->image()
            ->imageEditor()
            ->avatar()
            ->circleCropper()
            ->nullable();
    }

    protected function getDodidFormComponent(): Component
    {
        return TextInput::make('dodid')
            ->label('DoD ID')
            ->disabled()
            ->readOnly()
            ->required();
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Official Email')
            ->disabled()
            ->readOnly()
            ->required();
    }

    protected function getFirstNameFormComponent(): Component
    {
        return TextInput::make('first_name')
            ->label('First Name')
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getMiddleNameFormComponent(): Component
    {
        return TextInput::make('middle_name')
            ->label('Middle Name')
            ->nullable()
            ->maxLength(255);
    }

    protected function getLastNameFormComponent(): Component
    {
        return TextInput::make('last_name')
            ->label('Last Name')
            ->required()
            ->maxLength(255);
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('nickname')
            ->label('Nickname')
            ->required()
            ->maxLength(255);
    }

    protected function getEmailsFormComponent(): Component
    {
        return Repeater::make('emails')
            ->addActionLabel('Add email')
            ->columns(2)
            ->schema([
                TextInput::make('label')->required(),
                TextInput::make('email')->required(),
            ])
            ->collapsible()
            ->reorderableWithButtons();
    }

    protected function getPhoneNumbersFormComponent(): Component
    {
        return Repeater::make('phone_numbers')
            ->addActionLabel('Add phone number')
            ->columns(2)
            ->schema([
                TextInput::make('label')->required(),
                TextInput::make('phone_number')->required(),
            ])
            ->collapsible()
            ->reorderableWithButtons();
    }

    protected function getBranchFormComponent(): Component
    {
        return Select::make('branch_id')
            ->label('Branch')
            ->relationship(name: 'branch', titleAttribute: 'name')
            ->required();
    }

    protected function getRankFormComponent(): Component
    {
        return Select::make('rank_id')
            ->label('Rank')
            ->relationship(name: 'rank', titleAttribute: 'name')
            ->required();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getAvatarFormComponent(),
                $this->getDodidFormComponent(),
                $this->getEmailFormComponent(),
                Grid::make(2)
                    ->schema([
                        $this->getFirstNameFormComponent(),
                        $this->getMiddleNameFormComponent(),
                        $this->getLastNameFormComponent(),
                        $this->getNameFormComponent(),
                    ]),
                Grid::make(2)
                    ->schema([
                        $this->getBranchFormComponent(),
                        $this->getRankFormComponent(),
                    ]),
                $this->getEmailsFormComponent(),
                $this->getPhoneNumbersFormComponent(),
            ]);
    }
}
