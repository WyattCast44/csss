<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Organization;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Facades\Auth;

class CreateOrganizationPage extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Create a new organization';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Unit Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('abbr')
                    ->label('Unit Abbreviation')
                    ->required()
                    ->maxLength(255),
                TextInput::make('description')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    protected function handleRegistration(array $data): Organization
    {
        $organization = Organization::create([
            'name' => $data['name'],
            'abbr' => $data['abbr'],
            'slug' => str($data['abbr'])->slug(),
            'description' => $data['description'],
            'personal' => false,
            'approved' => true,
        ]);

        $organization->users()->attach(Auth::user());

        $organization->fresh();

        $organization->load('users');

        return $organization;
    }
}
