<?php

namespace App\Filament\App\Pages;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class SecurityDashboard extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected string $view = 'filament.app.pages.security-dashboard';

    protected static ?string $title = 'Unit Security Dashboard';

    protected ?string $subheading = 'View unit security status';

    protected static string|\UnitEnum|null $navigationGroup = 'Dashboards';

    protected static ?string $navigationLabel = 'Security Dashboard';

    public function table(Table $table): Table
    {
        $users = User::query()
            ->whereHas('organizations', function ($query) {
                $query->where('organization_id', Filament::getTenant()->id);
            });

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
            ])
            ->filters([
                // ...
            ])
            ->recordActions([
                // ...
            ])
            ->toolbarActions([
                // ...
            ]);
    }
}
