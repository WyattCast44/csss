<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Database\Factories\AttachedUserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AttachedUser extends Model
{
    /** @use HasFactory<AttachedUserFactory> */
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
        'attached_by_id',
        'attached_at',
        'attached_until',
        'notes',
        'deleted_by_id',
    ];

    protected function casts(): array
    {
        return [
            'organization_id' => 'integer',
            'user_id' => 'integer',
            'attached_by_id' => 'integer',
            'attached_at' => 'datetime',
            'attached_until' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function booted(): void
    {
        static::deleting(function (AttachedUser $attachedUser) {
            $attachedUser->deleted_by_id = Auth::id() ?? User::getSystemUser()->id;
        });
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'attached_by_id');
    }
}
