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
                TextInput::make('name'),
            ]);
    }

    protected function handleRegistration(array $data): Organization
    {
        $organization = Organization::create([
            // 'name' => $data['name'],
            // 'owner_id' => Auth::user()->id,
            // 'personal' => false,
        ]);

        $organization->users()->attach(Auth::user());

        return $organization;
    }
}
