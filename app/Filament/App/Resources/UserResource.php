<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\UserResource\Pages\CreateUser;
use App\Filament\App\Resources\UserResource\Pages\EditUser;
use App\Filament\App\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use App\Rules\AllowedEmailDomain;
use App\Rules\ValidDodId;
use App\Support\Traits\HasOrganizationPermissions;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    use HasOrganizationPermissions;

    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Members';

    protected static ?string $navigationLabel = 'Members';

    protected static ?string $modelLabel = 'Member';

    protected static bool $isScopedToTenant = false;

    public static function canViewAny(): bool
    {
        return (new static)->checkPermission('user:view');
    }

    public static function canCreate(): bool
    {
        return (new static)->checkPermission('user:create');
    }

    public static function canEdit(Model $record): bool
    {
        return (new static)->checkPermission('user:update');
    }

    public static function canDelete(Model $record): bool
    {
        return (new static)->checkPermission('user:delete');
    }

    public static function canDeleteAny(): bool
    {
        return (new static)->checkPermission('user:delete');
    }

    public static function canForceDelete(Model $record): bool
    {
        return (new static)->checkPermission('user:delete');
    }

    public static function canForceDeleteAny(): bool
    {
        return (new static)->checkPermission('user:delete');
    }

    public static function canRestore(Model $record): bool
    {
        return (new static)->checkPermission('user:update');
    }

    public static function canRestoreAny(): bool
    {
        return (new static)->checkPermission('user:update');
    }

    public static function canExport(): bool
    {
        return (new static)->checkPermission('user:export');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('dodid')
                    ->label('DOD ID')
                    ->nullable()
                    ->maxLength(255)
                    ->rules([
                        new ValidDodId,
                    ])
                    ->unique(ignoreRecord: true),
                TextInput::make('first_name')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('middle_name')
                    ->label('Middle Name')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('last_name')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('nickname')
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->rules([
                        new AllowedEmailDomain,
                    ]),
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
                Select::make('rank_id')
                    ->relationship('rank', 'name')
                    ->required(),
                TextInput::make('job_duty_code')
                    ->maxLength(255)
                    ->nullable(),
                FileUpload::make('avatar')
                    ->label('Avatar')
                    ->image()
                    ->imageEditor()
                    ->avatar()
                    ->circleCropper()
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('display_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('nickname')
                    ->label('Nickname')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('dodid')
                    ->label('DOD ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->copyable()
                    ->icon('heroicon-o-clipboard-document-list'),
                TextColumn::make('branch.abbr')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('rank.abbr')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('job_duty_code')
                    ->label('AFSC')
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
                EditAction::make()
                    ->visible(fn (User $record): bool => static::canEdit($record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => static::canDeleteAny()),
                    ForceDeleteBulkAction::make()
                        ->visible(fn (): bool => static::canForceDeleteAny()),
                    RestoreBulkAction::make()
                        ->visible(fn (): bool => static::canRestoreAny()),
                ]),
            ])->modifyQueryUsing(function (Builder $query) {
                return $query->whereHas('organizations', function (Builder $query) {
                    $query->where('organization_id', Filament::getTenant()->id);
                });
            });
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getRecordSubNavigation($page): array
    {
        return $page->generateNavigationItems([
            EditUser::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        return $query;
    }
}
