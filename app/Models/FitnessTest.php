<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Database\Factories\FitnessTestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FitnessTest extends Model
{
    /** @use HasFactory<FitnessTestFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    use LogsActivity;

    /*
    |-------------------------------------
    | Configuration
    |-------------------------------------
    */
    protected $fillable = [
        'ulid',
        'user_id',
        'date',
        'results',
        'score',
        'notes',
        'test_location',
        'passed',
        'attachments',
        'next_test_date',
        'next_test_created',
        'next_test_id',
        'input_by_id',
    ];

    protected $casts = [
        'results' => 'array',
        'attachments' => 'array',
        'next_test_created' => 'boolean',
        'next_test_id' => 'integer',
        'input_by_id' => 'integer',
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
    public function nextTest(): BelongsTo
    {
        return $this->belongsTo(PendingFitnessTest::class, 'next_test_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inputter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'input_by_id');
    }
}
