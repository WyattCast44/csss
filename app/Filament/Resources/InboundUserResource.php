<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InboundUserResource\Pages;
use App\Models\InboundUser;
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

class InboundUserResource extends Resource
{
    protected static ?string $model = InboundUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInboundUsers::route('/'),
            'create' => Pages\CreateInboundUser::route('/create'),
            'edit' => Pages\EditInboundUser::route('/{record}/edit'),
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
