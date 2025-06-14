<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttachedUserResource\Pages;
use App\Models\AttachedUser;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AttachedUserResource extends Resource
{
    protected static ?string $model = AttachedUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Members';

    protected static ?string $navigationLabel = 'Attached Members';

    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttachedUsers::route('/'),
            'create' => Pages\CreateAttachedUser::route('/create'),
            'edit' => Pages\EditAttachedUser::route('/{record}/edit'),
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
