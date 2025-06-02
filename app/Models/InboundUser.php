<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InboundUser extends Model
{
    /** @use HasFactory<\Database\Factories\InboundUserFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    use LogsActivity;

    /*
    |-------------------------------------
    | Configuration
    |-------------------------------------
    */
    protected $fillable = [
        'organization_id',
        'user_id',
        'report_date',
        'losing_organization_id',
        'sponsor_id',
        'notes',
    ];

    protected $casts = [
        'report_date' => 'datetime',
    ];

    protected $appends = [
        'days_until_report',
    ];

    protected static function booted(): void
    {
        static::created(function (InboundUser $inboundUser) {
            $inboundUser->assignActiveInprocessingActions();
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    /*
    |-------------------------------------
    | Accessors
    |-------------------------------------
    */
    public function daysUntilReport(): Attribute
    {
        $reportDate = Carbon::parse($this->attributes['report_date']);

        $daysUntilReport = round(now()->diffInDays($reportDate));

        if ($reportDate->isPast()) {
            $daysUntilReport = 'Overdue';
        }

        return Attribute::make(
            get: fn () => $daysUntilReport,
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

    public function losingOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'losing_organization_id');
    }

    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inprocessingActions(): BelongsToMany
    {
        return $this->belongsToMany(InprocessingAction::class)
            ->using(InboundUserInprocessingAction::class)
            ->withPivot(['completed', 'completed_at', 'completed_by_id', 'notes', 'inprocessing_organization_id'])
            ->withTimestamps();
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by_id');
    }

    /*
    |-------------------------------------
    | Private Methods
    |-------------------------------------
    */
    private function assignActiveInprocessingActions(): void
    {
        $activeActions = $this->organization->inprocessingActions()
            ->where('active', true)
            ->get();

        $this->inprocessingActions()->attach($activeActions, [
            'inprocessing_organization_id' => $this->organization_id,
        ]);
    }
}
