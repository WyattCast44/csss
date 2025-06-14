<?php

namespace App\Jobs;

use App\Models\OutboundUser;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Log;

class OutprocessOutboundUsersByReportDate implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public function middleware(): array
    {
        return [(new WithoutOverlapping('outprocess-outbound-users-by-report-date'))->releaseAfter(60)];
    }

    public function handle(): void
    {
        Log::info('Outprocessing outbound users by report date');

        $systemUser = User::where('dodid', User::SYSTEM_USER_DODID)->first();

        OutboundUser::query()
            ->whereNowOrPast('losing_date')
            ->whereNull('outprocess_at')
            ->lazyById(100, column: 'id')
            ->each->update([
                'outprocess_at' => now(),
                'outprocess_by_id' => $systemUser->id,
            ]);

        Log::info('Outprocessed outbound users by report date');
    }
}
