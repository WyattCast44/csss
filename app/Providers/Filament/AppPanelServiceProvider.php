<?php

namespace App\Providers\Filament;

use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Enums\Platform;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelServiceProvider extends PanelProvider
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
            ->configureDeveloperTools($panel);

        return $panel
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ]);
    }

    private function configurePanel(Panel $panel): self
    {
        $panel
            ->default()
            ->id('app')
            ->path('app')
            ->spa()
            ->favicon(asset('logo-dark.png'))
            ->brandLogo(asset('logo-dark.png'))
            ->brandLogoHeight('3rem');

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
            ->maxContentWidth(MaxWidth::SevenExtraLarge)
            ->colors([
                'primary' => Color::Blue,
                'danger' => Color::Rose,
            ]);

        return $this;
    }

    private function configureDiscovery(Panel $panel): self
    {
        $panel
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets');

        return $this;
    }

    private function configureAuthenticationFeatures(Panel $panel): self
    {
        $panel
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->profile();

        return $this;
    }

    private function configureTenancy(Panel $panel): self
    {
        // $panel
        //     ->tenant(Team::class)
        //     ->tenantRegistration(CreateTeamPage::class)
        //     ->tenantProfile(EditTeamPage::class)
        //     ->tenantMiddleware([
        //         ApplyTenantScopes::class,
        //     ], isPersistent: true);

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
            // NavigationGroup::make()
            //     ->label('Finance')
            //     ->icon('heroicon-o-banknotes'),
        ]);

        return $this;
    }

    private function configureDeveloperTools(Panel $panel): self
    {
        if (!app()->environment('local')) {
            return $this;
        }

        $panel->plugins([
            FilamentDeveloperLoginsPlugin::make()
                ->enabled(app()->environment('local'))
                ->users([
                    'John Doe' => 'john.doe@us.af.mil',
                ]),
        ]);

        return $this;
    }
}
