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
use Illuminate\Support\Facades\Auth;
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
        'inprocess_at',
        'inprocess_by_id',
    ];

    protected $casts = [
        'report_date' => 'datetime',
        'inprocess_at' => 'datetime',
        'inprocess_by_id' => 'integer',
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
    public function inprocessingActions(): BelongsToMany
    {
        return $this->belongsToMany(InprocessingAction::class)
            ->using(InboundUserInprocessingAction::class)
            ->withPivot(['completed', 'completed_at', 'completed_by_id', 'notes', 'inprocessing_organization_id'])
            ->withTimestamps();
    }

    public function inprocessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inprocess_by_id');
    }

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

    /*
    |-------------------------------------
    | Public Methods
    |-------------------------------------
    */
    public function inprocess(): void
    {
        $organization = $this->organization;

        // we need to attach the user to the organization
        $organization->users()->attach($this->user_id);

        // if the report date is in the past or today, we need to delete the inbound user
        if ($this->report_date->isPast() || $this->report_date->isToday()) {
            $this->delete();
        }

        // otherwise, we can keep the record for tracking purposes
        $this->update([
            'inprocess_at' => now(),
            'inprocess_by_id' => Auth::id() ?? null,
        ]);
    }
}
