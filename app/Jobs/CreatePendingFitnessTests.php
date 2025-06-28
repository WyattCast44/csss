<?php

namespace App\Jobs;

use App\Models\FitnessTest;
use App\Models\PendingFitnessTest;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Log;

class CreatePendingFitnessTests implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public function middleware(): array
    {
        return [(new WithoutOverlapping('create-pending-fitness-tests'))->releaseAfter(60)];
    }

    public function handle(): void
    {
        Log::info('Creating pending fitness tests');

        FitnessTest::query()
            ->where('next_test_created', false)
            ->lazyById(100, column: 'id')
            ->each(function (FitnessTest $fitnessTest) {
                $pendingFitnessTest = PendingFitnessTest::create([
                    'user_id' => $fitnessTest->user_id,
                    'due_date' => $fitnessTest->next_test_date,
                    'previous_test_id' => $fitnessTest->id,
                ]);

                $fitnessTest->update(['next_test_created' => true, 'next_test_id' => $pendingFitnessTest->id]);
            });

        Log::info('Created pending fitness tests');
    }
}
