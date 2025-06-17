<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Database\Factories\PurchaseRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseRequest extends Model
{
    /** @use HasFactory<PurchaseRequestFactory> */
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
        'name',
        'description',
        'category_id',
        'quantity',
        'unit_price',
        'est_total_price',
        'money_source',
        'link',
        'requires_contract',
        'building_id',
        'room_id',
        'notes',
        'attachments',
        'status',
        'approval_notes',
        'actioned_by_id',
        'actioned_at',
        'shipped_date',
        'recieved_date',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'quantity' => 'integer',
        'unit_price' => 'integer',
        'est_total_price' => 'integer',
        'requires_contract' => 'boolean',
        'attachments' => 'array',
        'actioned_at' => 'datetime',
        'shipped_date' => 'date',
        'recieved_date' => 'date',
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
    public function actionedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actioned_by_id');
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PurchaseCategory::class);
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
