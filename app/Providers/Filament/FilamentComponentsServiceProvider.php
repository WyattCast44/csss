<?php

namespace App\Providers\Filament;

use Filament\Schemas\Components\Section;
use Illuminate\Support\ServiceProvider;

class FilamentComponentsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Section::configureUsing(function (Section $section) {
            $section->compact();
        });
    }
}