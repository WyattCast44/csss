<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Organization extends Model implements HasAvatar
{
    /** @use HasFactory<\Database\Factories\OrganizationFactory> */
    use HasFactory, HasSlug, HasUlids, Notifiable, SoftDeletes;

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
        'pas_codes',
        'mailing_addresses',
        'physical_addresses',
        'email',
        'phone_numbers',
        'avatar',
        'approved',
        'personal',
        'branch_id',
        'command_id',
        'level_id',
        'parent_id',
    ];

    protected $casts = [
        'mailing_addresses' => 'array',
        'physical_addresses' => 'array',
        'pas_codes' => 'array',
        'emails' => 'array',
        'phone_numbers' => 'array',
        'approved' => 'boolean',
        'personal' => 'boolean',
        'branch_id' => 'integer',
        'command_id' => 'integer',
        'level_id' => 'integer',
        'parent_id' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar ? Storage::url($this->avatar) : null;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('abbr')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
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

    public function buildings(): HasMany
    {
        return $this->hasMany(Building::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    public function command(): BelongsTo
    {
        return $this->belongsTo(OrganizationCommand::class);
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

    public function purchaseRequests(): HasMany
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function safes(): HasMany
    {
        return $this->hasMany(Safe::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
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
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approved', true);
    }

    public function scopePersonal(Builder $query): Builder
    {
        return $query->where('personal', true);
    }

    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeShared(Builder $query): Builder
    {
        return $query->where('personal', false);
    }
}
