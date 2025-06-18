<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EntryAccessList extends Model
{
    /** @use HasFactory<EntryAccessListFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    use LogsActivity;

    /*
    |-------------------------------------
    | Configuration
    |-------------------------------------
    */
    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'building_id',
        'room_id',
        'created_by_user_id',
        'start_date',
        'end_date',
        'active',
        'locked',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'active' => 'boolean',
        'locked' => 'boolean',
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
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
    
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
