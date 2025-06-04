<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Organization extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationFactory> */
    use HasFactory, HasUlids, Notifiable, SoftDeletes;

    use LogsActivity;

    /*
    |-------------------------------------
    | Configuration
    |-------------------------------------
    */
    protected $fillable = [
        'name',
        'abbr',
        'slug',
        'description',
        'pas_code',
        'mailing_addresses',
        'physical_addresses',
        'email',
        'phone_numbers',
        'avatar',
        'approved',
        'personal',
        'branch_id',
        'level_id',
        'parent_id',
    ];

    protected $casts = [
        'mailing_addresses' => 'array',
        'physical_addresses' => 'array',
        'emails' => 'array',
        'phone_numbers' => 'array',
        'approved' => 'boolean',
        'personal' => 'boolean',
        'branch_id' => 'integer',
        'level_id' => 'integer',
        'parent_id' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    /*
    |-------------------------------------
    | Relationships
    |-------------------------------------
    */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function inboundUsers(): HasMany
    {
        return $this->hasMany(InboundUser::class);
    }

    public function inprocessingActions(): HasMany
    {
        return $this->hasMany(InprocessingAction::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(OrganizationLevel::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /*
    |-------------------------------------
    | Scopes
    |-------------------------------------
    */
    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeShared(Builder $query): Builder
    {
        return $query->where('personal', false);
    }

    public function scopePersonal(Builder $query): Builder
    {
        return $query->where('personal', true);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approved', true);
    }
}
