<?php

namespace App\Filament\App\Widgets;

use App\Models\PendingFitnessTest;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PendingFitnessTestsWidget extends BaseWidget
{
    public static function shouldRender(): bool
    {
        return true;

        // 1 get the users in the organization
        $users = User::query()
            ->whereHas('organizations', function ($query) {
                $query->where('organization_id', Filament::getTenant()->id);
            })->get();

        // 2 get the pending fitness tests for the users
        $pendingFitnessTests = PendingFitnessTest::query()
            ->whereIn('user_id', $users->pluck('id'))
            ->get();

        return $pendingFitnessTests->count() > 0;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('user.display_name')
                    ->sortable(),
                TextColumn::make('due_date')
                    ->date('M j, Y')
                    ->sortable(),
                TextColumn::make('previousTest.score')
                    ->label('Previous Score')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state >= 90 => 'success',
                        $state >= 75 => 'warning',
                        $state < 75 => 'danger',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('due_date', 'desc')
            ->paginated(false)
            ->emptyStateHeading('No tests due in the next 60 days');
    }

    protected function getTableQuery(): Builder
    {
        $users = User::query()
            ->whereHas('organizations', function ($query) {
                $query->where('organization_id', Filament::getTenant()->id);
            })->get();

        // 2 get the pending fitness tests for the users
        $pendingFitnessTests = PendingFitnessTest::query()
            ->whereIn('user_id', $users->pluck('id'))
            ->with('previousTest')
            ->where('due_date', '<=', now()->addDays(60))
            ->limit(5);

        return $pendingFitnessTests;
    }
}
