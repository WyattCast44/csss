<?php

namespace App\Providers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this
            ->configureRoutes()
            ->configureMorphMaps()
            ->configureModels()
            ->configureUrls()
            ->configureCommands()
            ->configureDates()
            ->configureRequests()
            ->configureTelescope()
            ->configureDebugbar(active: false);
    }

    private function configureMorphMaps(): self
    {
        Relation::enforceMorphMap([
            'user' => User::class,
        ]);

        return $this;
    }

    private function configureModels(): self
    {
        Model::shouldBeStrict(! app()->isProduction());

        return $this;
    }

    private function configureRoutes(): self
    {
        return $this;
    }

    private function configureUrls(): self
    {
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

        return $this;
    }

    private function configureCommands(): self
    {
        // DB::prohibitDestructiveCommands(
        //     app()->isProduction()
        // ); will reenable this when we actually have a production server

        return $this;
    }

    private function configureDates(): self
    {
        Date::use(CarbonImmutable::class);

        return $this;
    }

    private function configureRequests(): self
    {
        RequestException::dontTruncate();

        return $this;
    }

    private function configureTelescope(): self
    {
        // if ($this->app->environment('local') && class_exists(TelescopeServiceProvider::class)) {
        //     $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        //     $this->app->register(TelescopeServiceProvider::class);
        // }

        return $this;
    }

    private function configureDebugbar(bool $active = false): self
    {
        if ($active) {
            /** @phpstan-ignore-next-line */
            \Debugbar::enable();
        } else {
            /** @phpstan-ignore-next-line */
            \Debugbar::disable();
        }

        return $this;
    }
}
