<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Database\Factories\InprocessingActionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InprocessingAction extends Model
{
    /** @use HasFactory<InprocessingActionFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    use LogsActivity;

    /*
    |-------------------------------------
    | Configuration
    |-------------------------------------
    */
    protected $fillable = [
        'ulid',
        'organization_id',
        'name',
        'description',
        'category_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'category_id' => 'integer',
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
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProcessingActionCategory::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function inboundUsers(): BelongsToMany
    {
        return $this->belongsToMany(InboundUser::class)
            ->using(InboundUserInprocessingAction::class)
            ->withPivot(['completed', 'completed_at', 'completed_by_id', 'notes', 'inprocessing_organization_id'])
            ->withTimestamps();
    }
}
