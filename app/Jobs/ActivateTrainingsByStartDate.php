<?php

namespace App\Jobs;

use App\Models\GlobalTraining;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Log;

class ActivateTrainingsByStartDate implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public function middleware(): array
    {
        return [(new WithoutOverlapping('activate-trainings-by-start-date'))->releaseAfter(60)];
    }

    public function handle(): void
    {
        Log::info('Activating trainings by start date');

        GlobalTraining::query()
            ->whereNowOrPast('start_date')
            ->where('active', false)
            ->where('deactivated', false)
            ->where('end_date', '>=', now())
            ->lazyById(100, column: 'id')
            ->each(function (GlobalTraining $training) {
                Log::info('Activating training: '.$training->id);

                $training->update(['active' => true]);

                Log::info('Training activated: '.$training->id);
            });

        Log::info('Activated trainings by start date');
    }
}
