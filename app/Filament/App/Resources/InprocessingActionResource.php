<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\InprocessingActionResource\Pages\CreateInprocessingAction;
use App\Filament\App\Resources\InprocessingActionResource\Pages\EditInprocessingAction;
use App\Filament\App\Resources\InprocessingActionResource\Pages\ListInprocessingActions;
use App\Models\InprocessingAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\RichEditor;
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

class InprocessingActionResource extends Resource
{
    protected static ?string $model = InprocessingAction::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Action Name')
                    ->required()
                    ->maxLength(255)
                    ->helperText('For example, "Security Awareness Training"'),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->helperText('For example, "Security", "Training", "Other"')
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Category Name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('For example, "Security", "Training", "Other"'),
                        Select::make('organization_id')
                            ->relationship('organization', 'name')
                            ->required()
                            ->default(Filament::getTenant()->id),
                    ]),
                Toggle::make('active')
                    ->required(),
                RichEditor::make('description')
                    ->columnSpanFull()
                    ->nullable()
                    ->disableToolbarButtons([
                        'attachFiles',
                        'codeBlock',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('category.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->badge(),
                IconColumn::make('active')
                    ->boolean()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->toggleable()
                    ->wrap()
                    ->limit(50),
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
            'index' => ListInprocessingActions::route('/'),
            'create' => CreateInprocessingAction::route('/create'),
            'edit' => EditInprocessingAction::route('/{record}/edit'),
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
