<?php

use App\Http\Middleware\ApplyTenantScopes;

arch()->preset()->php();

arch()->preset()->laravel()
    ->ignoring(ApplyTenantScopes::class);

arch()->preset()->security();
