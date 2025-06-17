<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\OutboundUserResource\Pages\CreateOutboundUser;
use App\Filament\App\Resources\OutboundUserResource\Pages\EditOutboundUser;
use App\Filament\App\Resources\OutboundUserResource\Pages\ListOutboundUsers;
use App\Models\Organization;
use App\Models\OutboundUser;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OutboundUserResource extends Resource
{
    protected static ?string $model = OutboundUser::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-minus';

    protected static string|\UnitEnum|null $navigationGroup = 'Members';

    protected static ?string $navigationLabel = 'Outbound';

    protected static ?string $modelLabel = 'Outbound Member';

    protected static ?int $navigationSort = 3;

    protected static bool $isScopedToTenant = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'display_name', modifyQueryUsing: function (Builder $query) {
                        $currentOutboundUsers = OutboundUser::where('organization_id', Filament::getTenant()->id)->pluck('user_id');

                        return $query
                            ->withoutGlobalScopes(['currentOrganization'])
                            ->whereHas('organizations', function (Builder $query) {
                                return $query->where('organization_id', Filament::getTenant()->id);
                            })->whereNotIn('id', $currentOutboundUsers);
                    })
                    ->getOptionLabelFromRecordUsing(function (User $record) {
                        $dodid = $record->dodid ? ' ('.$record->dodid.')' : '';

                        return $record->display_name.$dodid;
                    })
                    ->required()
                    ->searchable()
                    ->preload(),
                DatePicker::make('losing_date')
                    ->required(),
                Select::make('gaining_organization_id')
                    ->relationship('gainingOrganization', 'name', modifyQueryUsing: function (Builder $query) {
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
                    ->searchable()
                    ->preload(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.display_name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('losing_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('gainingOrganization.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOutboundUsers::route('/'),
            'create' => CreateOutboundUser::route('/create'),
            'edit' => EditOutboundUser::route('/{record}/edit'),
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
