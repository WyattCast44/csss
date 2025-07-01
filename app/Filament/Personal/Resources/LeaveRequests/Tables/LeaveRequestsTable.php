<?php

namespace App\Filament\Personal\Resources\LeaveRequests\Tables;

use App\Enums\LeaveStatus;
use App\Models\LeaveRequest;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class LeaveRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('start_date')
                    ->date('m/d/Y')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('start_time')
                    ->dateTime('h:i A')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('end_date')
                    ->date('m/d/Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('end_time')
                    ->dateTime('h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('duration')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('type.name')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('notes')
                    ->limit(25)
                    ->tooltip(fn (LeaveRequest $record): string => $record->notes)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->sortable()
                    ->toggleable()
                    ->badge()
                    ->color(fn (LeaveStatus $state): string => match ($state) {
                        LeaveStatus::PENDING => 'warning',
                        LeaveStatus::APPROVED => 'success',
                        LeaveStatus::REJECTED => 'danger',
                        LeaveStatus::CANCELLED => 'danger',
                        LeaveStatus::COMPLETED => 'success',
                    })
                    ->formatStateUsing(fn (LeaveStatus $state): string => $state->label()),
                TextColumn::make('approved_at')
                    ->dateTime('m/d/Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('approved_by.display_name')
                    ->label('Approved By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime('m/d/Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('m/d/Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime('m/d/Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->slideOver(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
