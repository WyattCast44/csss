<?php

namespace App\Filament\Pages\Auth;

use App\Models\Branch;
use App\Models\Rank;
use App\Rules\AllowedEmailDomain;
use App\Rules\ValidDodId;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class Register extends \Filament\Auth\Pages\Register
{
    protected function getDodidFormComponent(): Component
    {
        return TextInput::make('dodid')
            ->label('DoD ID')
            ->required()
            ->maxLength(10)
            ->unique('users', 'dodid')
            ->rules([
                new ValidDodId,
            ])
            ->validationMessages([
                'unique' => 'DOD ID has already been registered.',
            ]);
    }

    protected function getFirstNameFormComponent(): Component
    {
        return TextInput::make('first_name')
            ->label('First Name')
            ->required();
    }

    protected function getMiddleNameFormComponent(): Component
    {
        return TextInput::make('middle_name')
            ->label('Middle Name')
            ->nullable();
    }

    protected function getLastNameFormComponent(): Component
    {
        return TextInput::make('last_name')
            ->label('Last Name')
            ->required();
    }

    protected function getNicknameFormComponent(): Component
    {
        return TextInput::make('nickname')
            ->label('Nickname');
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email')
            ->required()
            ->email()
            ->unique()
            ->helperText('You must use a valid DoD email address to register.')
            ->rules([
                new AllowedEmailDomain,
            ]);
    }

    protected function getBranchFormComponent(): Component
    {
        return Select::make('branch_id')
            ->label('Branch')
            ->options(Branch::select('name', 'id')->get()->pluck('name', 'id'))
            ->required();
    }

    protected function getRankFormComponent(): Component
    {
        $officerRanks = Rank::officer()->select('name', 'id')->get();
        $enlistedRanks = Rank::enlisted()->select('name', 'id')->get();
        $otherRanks = Rank::civilian()->select('name', 'id')->get();

        return Select::make('rank_id')
            ->label('Rank')
            ->options([
                'Officer' => $officerRanks->pluck('name', 'id')->toArray(),
                'Enlisted' => $enlistedRanks->pluck('name', 'id')->toArray(),
                'Other' => $otherRanks->pluck('name', 'id')->toArray(),
            ])
            ->required();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getDodidFormComponent(),
                Grid::make(2)
                    ->schema([
                        $this->getFirstNameFormComponent(),
                        $this->getMiddleNameFormComponent(),
                        $this->getLastNameFormComponent(),
                        $this->getNicknameFormComponent(),
                    ]),
                $this->getEmailFormComponent(),
                Grid::make(2)
                    ->schema([
                        $this->getBranchFormComponent(),
                        $this->getRankFormComponent(),
                    ]),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
