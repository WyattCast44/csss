<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    protected function getDodidFormComponent(): Component
    {
        return TextInput::make('dodid')
            ->label('DOD ID')
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
        return TextInput::make('name')
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
    
    public function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema([
                $this->getDodidFormComponent(),
                $this->getEmailFormComponent(),
                $this->getFirstNameFormComponent(),
                $this->getLastNameFormComponent(),
                $this->getMiddleNameFormComponent(),
                $this->getNameFormComponent(),
                $this->getEmailsFormComponent(),
                $this->getPhoneNumbersFormComponent(),
            ]);
    }
}