<?php

namespace App\Filament\Personal\Pages;

use App\Filament\App\Pages\AppDashboard;
use App\Models\Branch;
use App\Models\Organization;
use App\Models\Rank;
use App\Models\User;
use App\Providers\Filament\AppPanelServiceProvider;
use App\Rules\ValidDodId;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ManageOrganizationsPage extends Page implements HasSchemas, HasTable, HasActions
{
    use InteractsWithSchemas, InteractsWithActions, InteractsWithTable;

    protected string $view = 'filament.personal.pages.manage-organizations-page';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static ?string $navigationLabel = 'Manage Organizations';

    protected static ?string $title = 'Manage Organizations';

    protected ?string $subheading = 'Manage your organizational relationships here.';

    public function getTableQuery(): Builder
    {
        return Organization::query()
            ->whereHas('users', function ($query) {
                $query->where('users.id', Auth::id());
            })->where('personal', false);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('abbr'),
            ])
            ->headerActions([
                $this->getCreateOrganizationAction(),
            ])
            ->filters([
                // ...
            ])
            ->recordActions([
                $this->getVisitOrganizationAction(),
                $this->getLeaveOrganizationAction(),
            ])
            ->toolbarActions([
                // ...
            ]);
    }

    protected function getVisitOrganizationAction(): Action
    {
        return Action::make('visit')
            ->label('Visit')
            ->color('primary')
            ->url(fn (Organization $record): string => AppDashboard::getUrl([
                'tenant' => $record,
            ], panel: 'app'));
    }

    protected function getLeaveOrganizationAction(): Action
    {
        return Action::make('leave')
            ->label('Leave')
            ->color('warning')
            ->disabled(function (Organization $record): bool {
                return $record->users()->count() === 1;
            })
            ->tooltip(function (Organization $record): string {
                if ($record->users()->count() === 1) {
                    return 'You cannot leave an organization that you are the only member of.';
                }

                return 'Leave the organization';
            })
            ->action(function (Organization $organization): void {
                if ($organization->users()->count() === 1) {
                    Notification::make()
                        ->title('Cannot leave organization')
                        ->body('You cannot leave an organization that you are the only member of. You must delete the organization instead.')
                        ->danger()
                        ->send();

                    return;
                } else {
                    $organization->users()->detach(Auth::id());
                    $organization->save();
                }
            });
    }

    protected function getCreateOrganizationAction(): Action
    {
        return Action::make('Create Organization')
            ->label('Create Organization')
            ->icon(Heroicon::OutlinedPlus)
            ->color('primary')
            ->slideOver()
            ->schema([
                TextInput::make('name')
                    ->label('Organization Name')
                    ->required(),
                TextInput::make('abbr')
                    ->label('Organization Abbreviation')
                    ->required(),
            ])
            ->action(function (array $data): void {
                $organization = Organization::create($data);
                $organization->users()->attach(Auth::id());
                $organization->save();
            });
    }
}
