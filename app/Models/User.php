<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
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
        ];
    }

    /*
    |-------------------------------------
    | Filament Configuration
    |-------------------------------------
    */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
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
}
