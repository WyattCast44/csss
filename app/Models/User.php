<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable implements FilamentUser, HasTenants, MustVerifyEmail
{
    use CausesActivity, LogsActivity;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    use HasUlids;

    /*
    |-------------------------------------
    | Configuration
    |-------------------------------------
    */
    protected $fillable = [
        'dodid',
        'name',
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'password',
        'avatar',
        'phone_numbers',
        'emails',
        'branch_id',
        'personal_organization_id',
        'current_organization_id',
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
            'personal_organization_id' => 'integer',
            'current_organization_id' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->createPersonalOrganization();
        });
    }

    /*
    |-------------------------------------
    | Activity Log Configuration
    |-------------------------------------
    */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    /*
    |-------------------------------------
    | Filament Configuration
    |-------------------------------------
    */
    public function getTenants(Panel $panel): Collection
    {
        return $this->organizations;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->organizations()->whereKey($tenant)->exists();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->personalOrganization;
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

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class)->withTimestamps();
    }

    public function personalOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'personal_organization_id');
    }

    /*
    |-------------------------------------
    | Private Methods
    |-------------------------------------
    */
    private function createPersonalOrganization(): Organization
    {
        $name = $this->last_name ? $this->last_name : $this->first_name ?? $this->name;

        $organization = Organization::create([
            'name' => $name.'\'s Organization',
            'abbr' => $name.'\'s Org',
            'slug' => str($name.'\'s Organization')->slug(),
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
