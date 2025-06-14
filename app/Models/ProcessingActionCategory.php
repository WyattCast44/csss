<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ProcessingActionCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ProcessingActionCategoryFactory> */
    use HasFactory, HasSlug, HasUlids,SoftDeletes;

    use LogsActivity;

    /*
    |-------------------------------------
    | Configuration
    |-------------------------------------
    */
    protected $fillable = [
        'name',
        'slug',
        'organization_id',
    ];

    protected $casts = [
        'organization_id' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    /*
    |-------------------------------------
    | Scopes
    |-------------------------------------
    */
    public function scopeGlobal(Builder $query): Builder
    {
        return $query->whereNull('organization_id');
    }

    public function scopeAvailable(Builder $query, Organization $organization): Builder
    {
        return $query->where(function (Builder $query) use ($organization) {
            $query->global()->orWhereHas('organization', function (Builder $query) use ($organization) {
                $query->where('organization_id', $organization->id);
            });
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
}
