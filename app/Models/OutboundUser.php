<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Database\Factories\OutboundUserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class OutboundUser extends Model
{
    /** @use HasFactory<OutboundUserFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    use LogsActivity;

    /*
    |-------------------------------------
    | Configuration
    |-------------------------------------
    */
    protected $fillable = [
        'ulid',
        'organization_id',
        'user_id',
        'losing_date',
        'gaining_organization_id',
        'notes',
        'outprocess_at',
        'outprocess_by_id',
    ];

    protected $casts = [
        'losing_date' => 'date',
        'outprocess_at' => 'datetime',
        'outprocess_by_id' => 'integer',
    ];

    protected $appends = [
        'days_until_losing',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    /*
    |-------------------------------------
    | Accessors
    |-------------------------------------
    */
    public function daysUntilLosing(): Attribute
    {
        $losingDate = Carbon::parse($this->attributes['losing_date']);

        $daysUntilLosing = round(now()->diffInDays($losingDate));

        if ($losingDate->isPast()) {
            $daysUntilLosing = 'Overdue';
        }

        return Attribute::make(
            get: fn () => $daysUntilLosing,
        )->shouldCache();
    }

    /*
    |-------------------------------------
    | Relationships
    |-------------------------------------
    */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function gainingOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'gaining_organization_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
