<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CommissionSource extends Model
{
    /** @use HasFactory<\Database\Factories\CommissionSourceFactory> */
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
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }
}
