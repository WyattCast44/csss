<?php

namespace App\Jobs;

use App\Models\GlobalTraining;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
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
        $globalTrainings = GlobalTraining::query()
            ->where('start_date', '<=', now())
            ->where('active', false)
            ->get();

        foreach ($globalTrainings as $training) {
            Log::info('Activating training: '.$training->id);

            $training->update([
                'active' => true,
            ]);

            Log::info('Training activated: '.$training->id);
        }

        Log::info('Activated '.$globalTrainings->count().' trainings');
    }
}
