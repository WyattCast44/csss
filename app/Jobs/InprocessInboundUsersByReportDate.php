<?php

namespace App\Jobs;

use App\Models\InboundUser;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Log;

class InprocessInboundUsersByReportDate implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public function middleware(): array
    {
        return [(new WithoutOverlapping('inprocess-inbound-users-by-report-date'))->releaseAfter(60)];
    }

    public function handle(): void
    {
        Log::info('Inprocessing inbound users by report date');

        InboundUser::query()
            ->whereNowOrPast('report_date')
            ->whereNull('inprocess_at')
            ->lazyById(100, column: 'id')
            ->each->inprocess();

        Log::info('Inprocessed inbound users by report date');
    }
}
