<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Exports\FitnessOverviewExporter;
use App\Models\User;
use Filament\Actions\ExportAction;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class FitnessDashboard extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-trophy';

    protected string $view = 'filament.app.pages.fitness-dashboard';

    protected static ?string $title = 'Unit Fitness Dashboard';

    protected ?string $subheading = 'View recent scores and upcoming test dates, only shows assigned members';

    protected static string|\UnitEnum|null $navigationGroup = 'Dashboards';

    protected static ?string $navigationLabel = 'Fitness Dashboard';

    public function table(Table $table): Table
    {
        $users = User::query()
            ->whereHas('organizations', function ($query) {
                $query->where('organization_id', Filament::getTenant()->id);
            });

        // need to get the users latest fitness test and the pending fitness test
        $users = $users->with(['fitnessTests' => function ($query) {
            $query->latest()->first();
        }])->with(['pendingFitnessTests' => function ($query) {
            $query->latest()->first();
        }]);

        return $table
            ->query($users)
            ->columns([
                TextColumn::make('display_name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('rank.abbr')
                    ->label('Rank')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('fitnessTests.score')
                    ->label('Latest Test Score')
                    ->sortable()
                    ->badge()
                    ->toggleable(),
                TextColumn::make('fitnessTests.created_at')
                    ->label('Test Taken')
                    ->date('M j, Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('pendingFitnessTests.due_date')
                    ->label('Next Test Due')
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('pendingFitnessTests.due_date', 'asc')
            ->filters([
                // ...
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(FitnessOverviewExporter::class)
                    ->label('Export'),
            ])
            ->toolbarActions([
                // ...
            ]);
    }
}
