<?php

namespace App\Providers;

use App\Jobs\ActivateTrainingsByStartDate;
use App\Jobs\DeactivateTrainingsByEndDate;
use App\Jobs\InprocessInboundUsersByReportDate;
use App\Jobs\OutprocessOutboundUsersByReportDate;
use App\Models\AttachedUser;
use App\Models\Base;
use App\Models\Branch;
use App\Models\GlobalTraining;
use App\Models\InboundUser;
use App\Models\InboundUserInprocessingAction;
use App\Models\InprocessingAction;
use App\Models\Organization;
use App\Models\OrganizationCommand;
use App\Models\OrganizationLevel;
use App\Models\OutboundUser;
use App\Models\ProcessingActionCategory;
use App\Models\Rank;
use App\Models\TrainingFormat;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
            ->configureDebugbar(active: false)
            ->configureScheduledTasks();
    }

    private function configureMorphMaps(): self
    {
        Relation::enforceMorphMap([
            'training_format' => TrainingFormat::class,
            'global_training' => GlobalTraining::class,
            'branch' => Branch::class,
            'organization_level' => OrganizationLevel::class,
            'organization' => Organization::class,
            'user' => User::class,
            'rank' => Rank::class,
            'base' => Base::class,
            'organization_command' => OrganizationCommand::class,
            'inbound_user' => InboundUser::class,
            'inprocessing_action' => InprocessingAction::class,
            'inbound_user_inprocessing_action' => InboundUserInprocessingAction::class,
            'outbound_user' => OutboundUser::class,
            'attached_user' => AttachedUser::class,
            'processing_action_category' => ProcessingActionCategory::class,
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
        if ($this->app->environment('local') && class_exists(TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

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

    private function configureScheduledTasks(): self
    {
        Schedule::job(new ActivateTrainingsByStartDate)
            ->everyTwoHours()
            ->timezone('America/New_York');

        Schedule::job(new DeactivateTrainingsByEndDate)
            ->everyTwoHours()
            ->timezone('America/New_York');

        Schedule::job(new InprocessInboundUsersByReportDate)
            ->everyTwoHours()
            ->withoutOverlapping();

        Schedule::job(new OutprocessOutboundUsersByReportDate)
            ->everyTwoHours()
            ->withoutOverlapping();

        return $this;
    }
}
