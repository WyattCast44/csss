<?php

namespace App\Models;

use App\Enums\RankType;
use App\Support\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Rank extends Model
{
    /** @use HasFactory<\Database\Factories\RankFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    use LogsActivity;

    /*
    |-------------------------------------
    | Configuration
    |-------------------------------------
    */
    protected $fillable = [
        'name',
        'abbr',
        'type',
        'branch_id',
    ];

    protected $casts = [
        'type' => RankType::class,
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
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /*
    |-------------------------------------
    | Scopes
    |-------------------------------------
    */
    public function scopeBranch(Builder $query, Branch $branch): Builder
    {
        return $query->where('branch_id', $branch->id);
    }

    public function scopeOfficer(Builder $query): Builder
    {
        return $query->where('type', RankType::OFFICER);
    }

    public function scopeEnlisted(Builder $query): Builder
    {
        return $query->where('type', RankType::ENLISTED);
    }

    public function scopeCivilian(Builder $query): Builder
    {
        return $query->where('type', RankType::CIVILIAN)
            ->orWhere('type', RankType::OTHER)
            ->orWhereNull('type');
    }
}
