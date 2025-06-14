<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use App\Rules\AllowedEmailDomain;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('dodid')
                    ->required()
                    ->maxLength(10)
                    ->unique(ignoreRecord: true),
                TextInput::make('first_name')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('middle_name')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('last_name')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('nickname')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->rules([
                        new AllowedEmailDomain,
                    ]),
                Select::make('branch_id')
                    ->relationship('branch', 'name'),
                Select::make('rank_id')
                    ->relationship('rank', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('dodid')
                    ->label('DOD ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->searchable(isIndividual: true),
                TextColumn::make('last_name')
                    ->searchable()
                    ->sortable()
                    ->searchable(isIndividual: true),
                TextColumn::make('first_name')
                    ->searchable(isIndividual: true)
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('nickname')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                IconColumn::make('email_verified')
                    ->label('Email Verified')
                    ->boolean()
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable(),
                TextColumn::make('branch.abbr')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('rank.abbr')
                    ->numeric()
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
            ->defaultSort('last_name', 'asc')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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
