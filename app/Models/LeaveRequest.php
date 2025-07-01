<?php

namespace App\Models;

use App\Enums\LeaveStatus;
use App\Support\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LeaveRequest extends Model
{
    /** @use HasFactory<\Database\Factories\LeaveRequestFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    use LogsActivity;

    /*
    |-------------------------------------
    | Configuration
    |-------------------------------------
    */
    protected $fillable = [
        'user_id',
        'type_id',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'duration',
        'notes',
        'requires_approval',
        'route_to',
        'approved',
        'approved_at',
        'approved_by',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'type_id' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration' => 'integer',
        'requires_approval' => 'boolean',
        'approved' => 'boolean',
        'approved_at' => 'datetime',
        'approved_by' => 'integer',
        'status' => LeaveStatus::class,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function booted(): void
    {
        static::creating(function (LeaveRequest $leaveRequest) {
            $leaveRequest->duration = self::calculateDuration($leaveRequest);

            if ($leaveRequest->requires_approval) {
                $leaveRequest->approved = false;
                $leaveRequest->status = LeaveStatus::PENDING;
            } else {
                $leaveRequest->approved = true;
                $leaveRequest->status = LeaveStatus::APPROVED;
            }
        });
    }

    /*
    |-------------------------------------
    | Relationships
    |-------------------------------------
    */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function routeTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'route_to', 'id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |-------------------------------------
    | Helpers
    |-------------------------------------
    */
    public static function calculateDuration(LeaveRequest $leaveRequest): int
    {
        try {
            if ($leaveRequest->start_date < $leaveRequest->end_date) {
                // If the start date is before the end date, calculate the duration
                $duration = abs($leaveRequest->end_date->diffInDays($leaveRequest->start_date));
            } elseif ($leaveRequest->start_date == $leaveRequest->end_date) {
                // If the start date is the same as the end date, set the duration to 1
                $duration = 1;
            } elseif ($leaveRequest->start_date > $leaveRequest->end_date) {
                // If the start date is after the end date, set the duration to 0
                $duration = 0;
            } else {
                // If the start date is not a date, set the duration to 0
                $duration = 0;
            }
        } catch (\Exception $e) {
            $duration = 0;
        }

        return $duration;
    }
}
