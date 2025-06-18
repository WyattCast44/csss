<?php

namespace App\Filament\Pages\Auth;

use Filament\Schemas\Schema;

class Login extends \Filament\Auth\Pages\Login
{
    // fill the email and password if in local environment
    public function mount(): void
    {
        parent::mount();

        if (app()->environment('local')) {
            $this->data['email'] = 'john.doe@us.af.mil';
            $this->data['password'] = 'password';
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }
}
