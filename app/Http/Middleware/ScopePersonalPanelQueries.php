<?php

namespace App\Http\Middleware;

use App\Models\FitnessTest;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ScopePersonalPanelQueries
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // we will be authenticated by the time we get here
        $user = Auth::user();

        FitnessTest::addGlobalScope(function (Builder $query) use ($user) {
            $query->where('user_id', $user->id);
        });

        return $next($request);
    }
}
