<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Organization extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    use HasUlids;

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
    ];

    protected $casts = [
        'mailing_addresses' => 'array',
        'physical_addresses' => 'array',
        'emails' => 'array',
        'phone_numbers' => 'array',
        'approved' => 'boolean',
        'personal' => 'boolean',
    ];

    /*
    |-------------------------------------
    | Relationships
    |-------------------------------------
    */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
