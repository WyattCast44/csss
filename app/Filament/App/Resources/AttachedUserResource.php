<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\AttachedUserResource\Pages\CreateAttachedUser;
use App\Filament\App\Resources\AttachedUserResource\Pages\EditAttachedUser;
use App\Filament\App\Resources\AttachedUserResource\Pages\ListAttachedUsers;
use App\Models\AttachedUser;
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
use Illuminate\Support\Facades\Auth;

class AttachedUserResource extends Resource
{
    protected static ?string $model = AttachedUser::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|\UnitEnum|null $navigationGroup = 'Members';

    protected static ?string $navigationLabel = 'Attached Members';

    protected static bool $isScopedToTenant = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'id', modifyQueryUsing: function (Builder $query) {
                        return $query
                            ->withoutGlobalScopes(['currentOrganization'])
                            ->where('users.id', '!=', Auth::id());
                    })
                    ->getOptionLabelFromRecordUsing(function (User $record) {
                        $dodid = $record->dodid ? ' ('.$record->dodid.')' : '';

                        return $record->display_name.$dodid;
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('attached_by_id')
                    ->relationship('attachedBy', 'id', modifyQueryUsing: function (Builder $query) {
                        // only show users that are in the current organization
                        return $query
                            ->whereHas('organizations', function (Builder $query) {
                                $query->where('organization_id', Filament::getTenant()->id);
                            });
                    })
                    ->getOptionLabelFromRecordUsing(function (User $record) {
                        return $record->display_name;
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('attached_at'),
                DatePicker::make('attached_until'),
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
                TextColumn::make('attachedBy.display_name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('attached_at')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('attached_until')
                    ->date()
                    ->sortable()
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
            'index' => ListAttachedUsers::route('/'),
            'create' => CreateAttachedUser::route('/create'),
            'edit' => EditAttachedUser::route('/{record}/edit'),
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
