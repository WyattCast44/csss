<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InboundUserResource\Pages;
use App\Models\InboundUser;
use App\Models\Organization;
use App\Models\User;
use App\Rules\AllowedEmailDomain;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class InboundUserResource extends Resource
{
    protected static ?string $model = InboundUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationGroup = 'Members';

    protected static ?string $navigationLabel = 'Inbound';

    protected static ?string $modelLabel = 'Inbound Member';

    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name', modifyQueryUsing: function (Builder $query) {
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
                        TextInput::make('name')
                            ->label('Display Name / Call Sign')
                            ->required()
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
                            ->approved()
                            ->with('branch');
                    })
                    ->getOptionLabelFromRecordUsing(function (Organization $record) {
                        return $record->name.' ('.$record->branch?->abbr.')';
                    })
                    ->required()
                    ->nullable()
                    ->searchable()
                    ->preload(),
                Select::make('sponsor_id')
                    ->relationship('sponsor', 'name', modifyQueryUsing: function (Builder $query) {
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
                    ->toggleable(),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewInboundUser::class,
            Pages\EditInboundUser::class,
            Pages\ManageInprocessingActions::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInboundUsers::route('/'),
            'create' => Pages\CreateInboundUser::route('/create'),
            'view' => Pages\ViewInboundUser::route('/{record}'),
            'edit' => Pages\EditInboundUser::route('/{record}/edit'),
            'inprocessing' => Pages\ManageInprocessingActions::route('/{record}/inprocessing'),
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
