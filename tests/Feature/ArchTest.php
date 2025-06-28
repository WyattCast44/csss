<?php

use App\Http\Middleware\ApplyTenantScopes;
use App\Http\Middleware\ScopePersonalPanelQueries;
use App\Providers\TelescopeServiceProvider;

arch()->preset()->php();

arch()->preset()->laravel()
    // have to ignore, since using middleware in non-traditional place
    ->ignoring(ApplyTenantScopes::class)
    // have to ignore, since using middleware in non-traditional place
    ->ignoring(ScopePersonalPanelQueries::class)
    // have to ignore, since registering Telescope in for local use only
    ->ignoring(TelescopeServiceProvider::class);

arch()->preset()->security();
