<?php

namespace App\Filament\Personal\Resources\LeaveRequests\Pages;

use App\Filament\Personal\Resources\LeaveRequests\LeaveRequestResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $authId = Auth::id();

        $data['user_id'] = $authId;

        return $data;
    }
}
