<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class InboundUserInprocessingAction extends Pivot
{
    protected $table = 'inbound_user_inprocessing_action';

    protected $fillable = [
        'inbound_user_id',
        'inprocessing_organization_id',
        'inprocessing_action_id',
        'completed',
        'completed_at',
        'completed_by_id',
        'notes',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function inboundUser(): BelongsTo
    {
        return $this->belongsTo(InboundUser::class);
    }

    public function inprocessingAction(): BelongsTo
    {
        return $this->belongsTo(InprocessingAction::class);
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by_id');
    }

    public function inprocessingOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'inprocessing_organization_id');
    }
}
