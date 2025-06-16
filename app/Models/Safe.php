<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Safe extends Model
{
    /** @use HasFactory<\Database\Factories\SafeFactory> */
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
        'active',
        'drawers',
        'number_drawers',
        'number_of_locks',
        'grade',
    ];

    protected $casts = [
        'active' => 'boolean',
        'building_id' => 'integer',
        'room_id' => 'integer',
        'drawers' => 'array',
        'number_drawers' => 'integer',
        'number_of_locks' => 'integer',
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

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
