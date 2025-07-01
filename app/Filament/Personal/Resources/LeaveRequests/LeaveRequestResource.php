<?php

namespace App\Filament\Personal\Resources\LeaveRequests;

use App\Filament\Personal\Resources\LeaveRequests\Pages\CreateLeaveRequest;
use App\Filament\Personal\Resources\LeaveRequests\Pages\EditLeaveRequest;
use App\Filament\Personal\Resources\LeaveRequests\Pages\ListLeaveRequests;
use App\Filament\Personal\Resources\LeaveRequests\Schemas\LeaveRequestForm;
use App\Filament\Personal\Resources\LeaveRequests\Tables\LeaveRequestsTable;
use App\Models\LeaveRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    public static function form(Schema $schema): Schema
    {
        return LeaveRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeaveRequestsTable::configure($table);
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
            'index' => ListLeaveRequests::route('/'),
            'create' => CreateLeaveRequest::route('/create'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
