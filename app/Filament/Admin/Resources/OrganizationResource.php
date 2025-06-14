<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrganizationResource\Pages;
use App\Models\Organization;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('abbr')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull(),
                Repeater::make('pas_codes')
                    ->addActionLabel('Add PAS Code')
                    ->schema([
                        TextInput::make('label')->required(),
                        TextInput::make('code')->required(),
                    ])
                    ->collapsible(),
                Repeater::make('mailing_addresses')
                    ->addActionLabel('Add mailing address')
                    ->columns(2)
                    ->schema([
                        TextInput::make('label')->required(),
                        TextInput::make('address')->required(),
                    ])
                    ->collapsible()
                    ->reorderableWithButtons(),
                Repeater::make('physical_addresses')
                    ->addActionLabel('Add physical address')
                    ->columns(2)
                    ->schema([
                        TextInput::make('label')->required(),
                        TextInput::make('address')->required(),
                    ])
                    ->collapsible()
                    ->reorderableWithButtons(),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Repeater::make('phone_numbers')
                    ->addActionLabel('Add phone number')
                    ->columns(2)
                    ->schema([
                        TextInput::make('label')->required(),
                        TextInput::make('phone_number')->required(),
                    ])
                    ->collapsible()
                    ->reorderableWithButtons(),
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
                Select::make('level_id')
                    ->relationship('level', 'name')
                    ->nullable(),
                Select::make('command_id')
                    ->relationship('command', 'name')
                    ->nullable(),
                Select::make('parent_id')
                    ->relationship('parent', 'name', modifyQueryUsing: fn (Builder $query) => $query->shared()->approved(), ignoreRecord: true)
                    ->nullable()
                    ->searchable()
                    ->preload(),
                TextInput::make('avatar')
                    ->maxLength(255),
                Toggle::make('approved')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Logo')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->circular()
                    ->checkFileExistence(false)
                    ->defaultImageUrl(url('images/dod.png')),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('abbr')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('branch.abbr')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('level.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('command.abbr')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('parent.abbr')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('approved')
                    ->boolean(),
                TextColumn::make('email')
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
                SelectFilter::make('branch_id')
                    ->label('Branch')
                    ->relationship('branch', 'name'),
                SelectFilter::make('level_id')
                    ->label('Org Level')
                    ->relationship('level', 'name'),
                SelectFilter::make('parent_id')
                    ->label('Parent')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload(),
                TrashedFilter::make(),
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
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])->shared();
    }
}
