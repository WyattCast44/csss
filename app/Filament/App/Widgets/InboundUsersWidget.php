<?php

namespace App\Filament\App\Widgets;

use App\Models\InboundUser;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class InboundUsersWidget extends BaseWidget
{
    public static function shouldRender(): bool
    {
        return InboundUser::query()
            ->where('organization_id', Filament::getTenant()->id)
            ->exists();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('user.display_name'),
                TextColumn::make('report_date')
                    ->date('M j, Y'),
                TextColumn::make('days_until_report')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state <= 30 => 'warning',
                        $state <= 15 => 'danger',
                        $state <= 0 => 'danger',
                        default => 'success',
                    }),
            ])
            ->defaultSort('report_date', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->paginated(false);
    }

    protected function getTableQuery(): Builder
    {
        return InboundUser::query()
            ->where('organization_id', Filament::getTenant()->id)
            ->limit(5);
    }
}
