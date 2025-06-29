<?php

namespace App\Providers\Filament;

use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
// use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Platform;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelServiceProvider extends PanelProvider
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
            ->configureMiddleware($panel)
            ->configureNavigationGroups($panel)
            ->configureDeveloperTools($panel)
            ->configurePlugins($panel);

        return $panel
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ]);
    }

    private function configurePanel(Panel $panel): self
    {
        $panel
            ->id('admin')
            ->path('admin')
            ->spa();

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
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverClusters(in: app_path('Filament/Admin/Clusters'), for: 'App\\Filament\\Admin\\Clusters')
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets');

        return $this;
    }

    private function configureAuthenticationFeatures(Panel $panel): self
    {
        $panel->login();

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
        ])->authMiddleware([
            Authenticate::class,
        ]);

        return $this;
    }

    private function configureNavigationGroups(Panel $panel): self
    {
        $panel->navigationGroups([
            NavigationGroup::make('Metadata'),
            NavigationGroup::make('Training'),
        ]);

        return $this;
    }

    private function configureDeveloperTools(Panel $panel): self
    {
        if (! app()->environment('local')) {
            return $this;
        }

        $panel->plugins([
            // FilamentDeveloperLoginsPlugin::make()
            //     ->enabled(app()->environment('local'))
            //     ->users([
            //         'John Doe' => 'john.doe@us.af.mil',
            //     ]),
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
