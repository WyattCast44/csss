<?php

namespace App\Jobs;

use App\Models\GlobalTraining;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Log;

class DeactivateTrainingsByEndDate implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public function middleware(): array
    {
        return [(new WithoutOverlapping('deactivate-trainings-by-end-date'))->releaseAfter(60)];
    }

    public function handle(): void
    {
        $globalTrainings = GlobalTraining::query()
            ->where('end_date', '<=', now())
            ->where('active', true)
            ->get();

        foreach ($globalTrainings as $training) {
            Log::info('Deactivating training: '.$training->id);

            $training->update([
                'active' => false,
            ]);

            Log::info('Training deactivated: '.$training->id);
        }

        Log::info('Deactivated '.$globalTrainings->count().' trainings');
    }
}
