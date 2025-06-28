<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\Register;
use App\Http\Middleware\ScopePersonalPanelQueries;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Platform;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class PersonalPanelServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $this
            ->configurePanel($panel)
            ->configureGlobalSearch($panel)
            ->configureDatabaseNotifications($panel)
            ->configurePanelStyling($panel)
            ->configureDiscovery($panel)
            ->configureAuthenticationFeatures($panel)
            ->configureTenancy($panel)
            ->configureMiddleware($panel)
            ->configureNavigationGroups($panel)
            ->configureDeveloperTools($panel)
            ->configurePlugins($panel);

        return $panel;
    }

    private function configurePanel(Panel $panel): self
    {
        $panel
            ->default()
            ->id('personal')
            ->path('')
            ->spa();

        $panel->pages([
            Dashboard::class,
        ]);

        return $this;
    }

    private function configureGlobalSearch(Panel $panel): self
    {
        $panel
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchFieldSuffix(fn (): ?string => match (Platform::detect()) {
                Platform::Windows, Platform::Linux => 'ctrl+k',
                Platform::Mac => '⌘K',
                default => null,
            });

        return $this;
    }

    private function configureDatabaseNotifications(Panel $panel): self
    {
        $panel->databaseNotifications();

        return $this;
    }

    private function configurePanelStyling(Panel $panel): self
    {
        $panel
            ->sidebarCollapsibleOnDesktop()
            ->defaultThemeMode(ThemeMode::Light)
            ->maxContentWidth(Width::SevenExtraLarge)
            ->colors([
                'primary' => Color::Blue,
                'danger' => Color::Rose,
            ])
            ->favicon(asset('logo-dark.png'))
            ->brandLogo(fn () => view('filament.logos.light'))
            ->darkModeBrandLogo(fn () => view('filament.logos.dark'))
            ->brandLogoHeight('2rem');

        return $this;
    }

    private function configureDiscovery(Panel $panel): self
    {
        $panel
            ->discoverResources(in: app_path('Filament/Personal/Resources'), for: 'App\Filament\Personal\Resources')
            ->discoverPages(in: app_path('Filament/Personal/Pages'), for: 'App\Filament\Personal\Pages')
            ->discoverWidgets(in: app_path('Filament/Personal/Widgets'), for: 'App\Filament\Personal\Widgets');

        return $this;
    }

    private function configureAuthenticationFeatures(Panel $panel): self
    {
        $panel
            ->login()
            ->registration(Register::class)
            ->passwordReset()
            ->login(Login::class)
            ->profile(EditProfile::class, isSimple: true);

        return $this;
    }

    private function configureTenancy(Panel $panel): self
    {
        // don't really need tenancy for personal panel
        // this panel will only be used for personal records
        // and will not be shared with other users

        return $this;
    }

    private function configureMiddleware(Panel $panel): self
    {
        $panel->middleware([
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
            ScopePersonalPanelQueries::class,
        ])->authMiddleware([
            Authenticate::class,
        ]);

        return $this;
    }

    private function configureNavigationGroups(Panel $panel): self
    {
        $panel->navigationGroups([
            //
        ]);

        return $this;
    }

    private function configureDeveloperTools(Panel $panel): self
    {
        if (! app()->environment('local')) {
            return $this;
        }

        $panel->plugins([
            //
        ]);

        return $this;
    }

    private function configurePlugins(Panel $panel): self
    {
        $panel->plugins([
            //
        ]);

        return $this;
    }
}
