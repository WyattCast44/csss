<?php

namespace App\Filament\Admin\Resources;

use App\Enums\TrainingFrequency;
use App\Filament\Admin\Resources\GlobalTrainingResource\Pages\CreateGlobalTraining;
use App\Filament\Admin\Resources\GlobalTrainingResource\Pages\EditGlobalTraining;
use App\Filament\Admin\Resources\GlobalTrainingResource\Pages\ListGlobalTrainings;
use App\Models\GlobalTraining;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GlobalTrainingResource extends Resource
{
    protected static ?string $model = GlobalTraining::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|\UnitEnum|null $navigationGroup = 'Training';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('description')
                    ->nullable(),
                TextInput::make('url')
                    ->label('Link / URL')
                    ->helperText('If you have a link to the training, enter the URL here. E.g. https://www.mylearning.mil/123456')
                    ->url()
                    ->nullable()
                    ->live(),
                TextInput::make('url_text')
                    ->label('Link Label')
                    ->helperText('If you have a link to the training, enter the label here. E.g. AFI 36-2905, etc. If no label is set, the URL will be used as the label.')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('source_document_text')
                    ->label('Source Document Label')
                    ->helperText('If you have a source document, enter the source document name here. E.g. AFI 36-2905, etc.')
                    ->maxLength(255)
                    ->nullable()
                    ->maxLength(255),
                TextInput::make('source_document_url')
                    ->label('Source Document Link / URL')
                    ->helperText('If you have a source document and a link to the source document, enter the URL here. E.g. https://www.af.mil/AFI/36-2905/')
                    ->url()
                    ->nullable()
                    ->live(),
                Select::make('format_id')
                    ->relationship('format', 'name'),
                Select::make('frequency')
                    ->options(TrainingFrequency::class)
                    ->nullable(),
                DatePicker::make('start_date')
                    ->helperText('If you have a start date when this training is available, enter the date here. We will automatically set the course as active on this date.'),
                DatePicker::make('end_date')
                    ->helperText('If you have an end date when this training is no longer available, enter the date here. We will automatically set the course as inactive on this date.'),
                Toggle::make('active')
                    ->required()
                    ->helperText('If this training is available to users, set this to active. Otherwise, set this to inactive. If it is active, users will see this training in the search results.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('url_text')
                    ->searchable(),
                TextColumn::make('source_document_url')
                    ->searchable(),
                TextColumn::make('source_document_text')
                    ->searchable(),
                TextColumn::make('format.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('frequency')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                IconColumn::make('active')
                    ->boolean(),
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
            'index' => ListGlobalTrainings::route('/'),
            'create' => CreateGlobalTraining::route('/create'),
            'edit' => EditGlobalTraining::route('/{record}/edit'),
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
