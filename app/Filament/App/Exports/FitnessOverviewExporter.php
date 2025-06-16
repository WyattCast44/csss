<?php

namespace App\Filament\App\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

class FitnessOverviewExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->whereHas('organizations', function ($query) {
            $query->where('organization_id', Filament::getTenant()->id);
        })->with(['fitnessTests' => function ($query) {
            $query->latest()->first();
        }])->with(['pendingFitnessTests' => function ($query) {
            $query->latest()->first();
        }]);
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('display_name')
                ->label('Name'),
            ExportColumn::make('rank.abbr')
                ->label('Rank'),
            ExportColumn::make('fitnessTests.score')
                ->label('Latest Test Score'),
            ExportColumn::make('fitnessTests.created_at')
                ->label('Test Taken'),
            ExportColumn::make('pendingFitnessTests.due_date')
                ->label('Next Test Due'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your export has completed and '.number_format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
