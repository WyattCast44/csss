<?php

namespace App\Http\Middleware;

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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentTeam = Filament::getTenant();

        // User::addGlobalScope('currentTeam', function (Builder $query) use ($currentTeam) {
        //     $query->whereHas('teams', function (Builder $query) use ($currentTeam) {
        //         $query->where('team_id', $currentTeam->id);
        //     });
        // });

        return $next($request);
    }
}
