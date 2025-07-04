<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasName, HasTenants
{
    use CausesActivity, LogsActivity;

    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUlids, Notifiable, SoftDeletes;

    use HasRoles;

    const SYSTEM_USER_DODID = '9999999999';

    /*
    |-------------------------------------
    | Configuration
    |-------------------------------------
    */
    protected $fillable = [
        'dodid',
        'first_name',
        'last_name',
        'middle_name',
        'nickname',
        'email',
        'email_verified_at',
        'personal_email',
        'personal_phone',
        'password',
        'avatar',
        'phone_numbers',
        'emails',
        'branch_id',
        'rank_id',
        'job_duty_code',
        'personal_organization_id',
        'current_organization_id',
        'has_email_authentication',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'phone_numbers' => 'array',
            'emails' => 'array',
            'branch_id' => 'integer',
            'rank_id' => 'integer',
            'personal_organization_id' => 'integer',
            'current_organization_id' => 'integer',
            'email_verified' => 'boolean',
            'has_email_authentication' => 'boolean',
        ];
    }

    protected $appends = [
        'email_verified',
    ];

    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->createPersonalOrganization();
            $user->assignRole('Member');
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
    protected function emailVerified(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->email_verified_at ? true : false,
        );
    }

    public static function getSystemUser(): self
    {
        return self::where('dodid', self::SYSTEM_USER_DODID)->firstOrFail();
    }

    public function hasPendingFitnessTest(): bool
    {
        return $this->pendingFitnessTests()->exists();
    }

    /*
    |-------------------------------------
    | Filament Configuration
    |-------------------------------------
    */
    public function getFilamentName(): string
    {
        return $this->display_name;
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->organizations;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return
            // if the tenant is in the user's organizations
            $this->organizations()->whereKey($tenant)->exists() ||
            // or if the tenant is the user's personal organization
            ($tenant->personal && $tenant->id === $this->personal_organization_id);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'app') {
            return true; // TODO: Decide if we want to allow non-admin users to access the app panel
        }

        if ($panel->getId() === 'admin') {
            return true; // TODO: remove this

            // return $this->isAdmin();
        }

        if ($panel->getId() === 'personal') {
            return true; // every user should have access to their personal panel
        }

        return false;
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->personalOrganization;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Super Admin');
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

    public function currentOrganization(): BelongsTo
    {
        if (! $this->current_organization_id) {
            $this->update([
                'current_organization_id' => $this->personalOrganization?->id,
            ]);
        }

        return $this->belongsTo(Organization::class, 'current_organization_id');
    }

    public function fitnessTests(): HasMany
    {
        return $this->hasMany(FitnessTest::class);
    }

    public function pendingFitnessTests(): HasMany
    {
        return $this->hasMany(PendingFitnessTest::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class)->withTimestamps();
    }

    public function personalOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'personal_organization_id');
    }

    public function rank(): BelongsTo
    {
        return $this->belongsTo(Rank::class);
    }

    public function entryAccessLists(): BelongsToMany
    {
        return $this->belongsToMany(EntryAccessList::class)
            ->withPivot(['added_by_user_id', 'added_at', 'removed_at', 'removed_by_user_id', 'notes'])
            ->withTimestamps();
    }

    /*
    |-------------------------------------
    | Private Methods
    |-------------------------------------
    */
    private function createPersonalOrganization(): Organization
    {
        $name = $this->first_name ? $this->first_name : $this->last_name;

        $teamName = $name.'\'s Personal Org';
        $teamAbbr = $name.'\'s Org';

        $organization = Organization::create([
            'name' => $teamName,
            'abbr' => $teamAbbr,
            'email' => $this->email,
            'description' => 'This is your personal organization. It is used to store your personal information.',
            'personal' => true,
            'approved' => true,
        ]);

        $this->update([
            'personal_organization_id' => $organization->id,
            'current_organization_id' => $organization->id,
        ]);

        $this->organizations()->attach($organization);

        return $organization;
    }
}
