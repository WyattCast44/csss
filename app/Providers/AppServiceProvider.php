<?php

namespace App\Providers;

use App\Jobs\ActivateTrainingsByStartDate;
use App\Jobs\CreatePendingFitnessTests;
use App\Jobs\DeactivateTrainingsByEndDate;
use App\Jobs\InprocessInboundUsersByReportDate;
use App\Jobs\OutprocessOutboundUsersByReportDate;
use App\Models\AttachedUser;
use App\Models\Base;
use App\Models\Branch;
use App\Models\FitnessTest;
use App\Models\GlobalTraining;
use App\Models\InboundUser;
use App\Models\InboundUserInprocessingAction;
use App\Models\InprocessingAction;
use App\Models\Organization;
use App\Models\OrganizationCommand;
use App\Models\OrganizationLevel;
use App\Models\OutboundUser;
use App\Models\PendingFitnessTest;
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
            'user' => User::class,
            'rank' => Rank::class,
            'base' => Base::class,
            'branch' => Branch::class,
            'inbound_user' => InboundUser::class,
            'organization' => Organization::class,
            'fitness_test' => FitnessTest::class,
            'outbound_user' => OutboundUser::class,
            'attached_user' => AttachedUser::class,
            'training_format' => TrainingFormat::class,
            'global_training' => GlobalTraining::class,
            'organization_level' => OrganizationLevel::class,
            'inprocessing_action' => InprocessingAction::class,
            'pending_fitness_test' => PendingFitnessTest::class,
            'organization_command' => OrganizationCommand::class,
            'processing_action_category' => ProcessingActionCategory::class,
            'inbound_user_inprocessing_action' => InboundUserInprocessingAction::class,
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
        $jobs = [
            // Training Jobs
            ActivateTrainingsByStartDate::class,
            DeactivateTrainingsByEndDate::class,

            // In/Outbound User Processing Jobs
            InprocessInboundUsersByReportDate::class,
            OutprocessOutboundUsersByReportDate::class,

            // Fitness Test Jobs
            CreatePendingFitnessTests::class,
        ];

        foreach ($jobs as $job) {
            Schedule::job($job)
                ->everyTwoHours()
                ->timezone('America/New_York');
        }

        return $this;
    }
}
