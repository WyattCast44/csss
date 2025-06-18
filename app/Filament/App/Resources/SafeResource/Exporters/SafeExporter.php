<?php

namespace App\Filament\App\Resources\SafeResource\Exporters;

use App\Models\Safe;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class SafeExporter extends Exporter
{
    protected static ?string $model = Safe::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')
                ->label('Safe Name'),
            ExportColumn::make('description')
                ->label('Description'),
            ExportColumn::make('building.name')
                ->label('Building'),
            ExportColumn::make('room.name')
                ->label('Room Name'),
            ExportColumn::make('room.number')
                ->label('Room Number'),
            ExportColumn::make('building.base.abbr')
                ->label('Base'),
            ExportColumn::make('number_drawers')
                ->label('Number of Drawers'),
            ExportColumn::make('number_of_locks')
                ->label('Number of Locks'),
            ExportColumn::make('grade')
                ->label('Grade'),
            ExportColumn::make('active')
                ->label('Active'),
            ExportColumn::make('created_at')
                ->label('Created At'),
            ExportColumn::make('updated_at')
                ->label('Updated At'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your safe export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
