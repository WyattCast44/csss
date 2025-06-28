<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\InboundUserResource\Pages\CreateInboundUser;
use App\Filament\App\Resources\InboundUserResource\Pages\EditInboundUser;
use App\Filament\App\Resources\InboundUserResource\Pages\ListInboundUsers;
use App\Filament\App\Resources\InboundUserResource\Pages\ManageInprocessingActions;
use App\Filament\App\Resources\InboundUserResource\Pages\ViewInboundUser;
use App\Models\InboundUser;
use App\Models\Organization;
use App\Models\User;
use App\Rules\AllowedEmailDomain;
use App\Rules\ValidDodId;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class InboundUserResource extends Resource
{
    protected static ?string $model = InboundUser::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

    protected static string|\UnitEnum|null $navigationGroup = 'Members';

    protected static ?string $navigationLabel = 'Inbound';

    protected static ?string $modelLabel = 'Inbound Member';

    protected static bool $isScopedToTenant = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'display_name', modifyQueryUsing: function (Builder $query) {
                        return $query
                            ->withoutGlobalScopes(['currentOrganization'])
                            ->where('users.id', '!=', Auth::id());
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(function (User $record) {
                        $dodid = $record->dodid ? ' ('.$record->dodid.')' : '';

                        return $record->display_name.$dodid;
                    })->createOptionForm([
                        TextInput::make('dodid')
                            ->label('DOD ID')
                            ->nullable()
                            ->maxLength(10)
                            ->unique('users', 'dodid')
                            ->rules([
                                new ValidDodId,
                            ])
                            ->validationMessages([
                                'unique' => 'DOD ID has already been registered.',
                            ]),
                        TextInput::make('first_name')
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('middle_name')
                            ->maxLength(255),
                        TextInput::make('last_name')
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('nickname')
                            ->label('Nickname')
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Official Email')
                            ->required()
                            ->helperText('You must use a valid DoD email address to register.')
                            ->rules([
                                new AllowedEmailDomain,
                            ])
                            ->unique('users', 'email')
                            ->validationMessages([
                                'unique' => 'Email has already been registered.',
                            ])
                            ->maxLength(255),
                        Select::make('branch_id')
                            ->relationship('branch', 'name'),
                        Select::make('rank_id')
                            ->relationship('rank', 'name'),
                        TextInput::make('job_duty_code')
                            ->label('AFSC / MOS / etc.')
                            ->maxLength(255),
                    ]),
                DatePicker::make('report_date'),
                Select::make('losing_organization_id')
                    ->relationship('losingOrganization', 'name', modifyQueryUsing: function (Builder $query) {
                        return $query
                            ->where('id', '!=', Filament::getTenant()->id)
                            ->where('personal', false)
                            ->with('branch');
                    })
                    ->getOptionLabelFromRecordUsing(function (Organization $record) {
                        return $record->name.' ('.$record->branch?->abbr.')';
                    })
                    ->required()
                    ->nullable()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Organization Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('abbr')
                            ->label('Abbreviation')
                            ->maxLength(255)
                            ->required(),
                        Select::make('branch_id')
                            ->relationship('branch', 'name')
                            ->required(),
                        Select::make('level_id')
                            ->label('Organization Level')
                            ->relationship('level', 'name')
                            ->required(),
                    ]),
                Select::make('sponsor_id')
                    ->relationship('sponsor', 'display_name', modifyQueryUsing: function (Builder $query) {
                        return $query
                            ->whereHas('organizations', function (Builder $query) {
                                $query->where('organization_id', Filament::getTenant()->id);
                            });
                    })
                    ->getOptionLabelFromRecordUsing(function (User $record) {
                        return $record->display_name.' ('.$record->dodid.')';
                    })
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.display_name')
                    ->label('Name')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('user.rank.abbr')
                    ->label('Rank')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('user.job_duty_code')
                    ->label('AFSC')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('report_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('days_until_report')
                    ->label('Days Until Report')
                    ->sortable()
                    ->toggleable()
                    ->badge(),
                TextColumn::make('losingOrganization.name')
                    ->searchable()
                    ->sortable()
                    ->limit(25)
                    ->tooltip(fn (InboundUser $record) => $record->losingOrganization->name)
                    ->toggleable(),
                TextColumn::make('sponsor.display_name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('inprocess_at')
                    ->label('Date Inprocessed')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('inprocessor.display_name')
                    ->label('Inprocessed By')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('inprocess')
                    ->visible(fn (InboundUser $record) => $record->inprocess_at === null)
                    ->requiresConfirmation()
                    ->modalDescription('Are you sure you want to add this user to the organization? This will add them to your members list. And they will be able to access the organization\'s resources. The inbound user record will continue to exist in the system until the report date is reached, or you archive it.')
                    ->icon('heroicon-o-user-plus')
                    ->color('warning')
                    ->action(function (InboundUser $record) {
                        $record->inprocess();

                        Notification::make()
                            ->title('User Added')
                            ->body('The user has been added to the organization.')
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewInboundUser::class,
            EditInboundUser::class,
            ManageInprocessingActions::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInboundUsers::route('/'),
            'create' => CreateInboundUser::route('/create'),
            'view' => ViewInboundUser::route('/{record}'),
            'edit' => EditInboundUser::route('/{record}/edit'),
            'inprocessing' => ManageInprocessingActions::route('/{record}/inprocessing'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
