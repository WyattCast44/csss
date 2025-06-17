<?php

namespace App\Models;

use App\Enums\TrainingFrequency;
use App\Support\Concerns\HasUlids;
use Database\Factories\GlobalTrainingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GlobalTraining extends Model
{
    /** @use HasFactory<GlobalTrainingFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    use LogsActivity;

    /*
    |----------------------------------------------------
    | Configuration
    |----------------------------------------------------
    */
    protected $fillable = [
        'title',
        'description',
        'url',
        'url_text',
        'source_document_url',
        'source_document_text',
        'format_id',
        'frequency',
        'start_date',
        'end_date',
        'active',
        'deactivated',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'active' => 'boolean',
        'frequency' => TrainingFrequency::class,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    /*
    |----------------------------------------------------
    | Relationships
    |----------------------------------------------------
    */
    public function format(): BelongsTo
    {
        return $this->belongsTo(TrainingFormat::class);
    }
}
