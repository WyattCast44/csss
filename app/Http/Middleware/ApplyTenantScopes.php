<?php

namespace App\Http\Middleware;

use App\Models\InboundUser;
use App\Models\User;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyTenantScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request):Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentOrganization = Filament::getTenant();

        // User::addGlobalScope('currentOrganization', function (Builder $query) use ($currentOrganization) {
        //     $query->whereHas('organizations', function (Builder $query) use ($currentOrganization) {
        //         $query->where('organization_id', $currentOrganization->id);
        //     });
        // });

        InboundUser::addGlobalScope('currentOrganization', function (Builder $query) use ($currentOrganization) {
            $query->where('organization_id', $currentOrganization->id);
        });

        return $next($request);
    }
}
