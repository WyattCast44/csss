<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InboundUserResource\Pages;
use App\Models\InboundUser;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                        return $query->withoutGlobalScopes(['currentOrganization']);
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(function (User $record) {
                        return $record->display_name.' ('.$record->dodid.')';
                    })->createOptionForm([
                        TextInput::make('dodid')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('first_name')
                            ->maxLength(255),
                        TextInput::make('last_name')
                            ->maxLength(255),
                        TextInput::make('middle_name')
                            ->maxLength(255),
                        TextInput::make('email')
                            ->maxLength(255),
                        TextInput::make('avatar')
                            ->maxLength(255),
                        TextInput::make('phone_numbers')
                            ->tel(),
                        TextInput::make('emails')
                            ->email(),
                        Select::make('branch_id')
                            ->relationship('branch', 'name'),
                        Select::make('rank_id')
                            ->relationship('rank', 'name'),
                        TextInput::make('job_duty_code')
                            ->maxLength(255),
                    ]),
                DatePicker::make('report_date'),
                Select::make('losing_organization_id')
                    ->relationship('losingOrganization', 'name')
                    ->required()
                    ->nullable()
                    ->searchable()
                    ->preload(),
                Select::make('sponsor_id')
                    ->relationship('sponsor', 'name')
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
                    ->sortable(),
                TextColumn::make('user.rank.abbr')
                    ->label('Rank')
                    ->sortable(),
                TextColumn::make('user.job_duty_code')
                    ->label('AFSC')
                    ->sortable(),
                TextColumn::make('report_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('days_until_report')
                    ->label('Days Until Report')
                    ->sortable(),
                TextColumn::make('losingOrganization.name')
                    ->sortable(),
                TextColumn::make('sponsor.display_name')
                    ->sortable(),
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
                Tables\Actions\EditAction::make(),
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
