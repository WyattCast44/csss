<?php

namespace App\Models;

use App\Support\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveType extends Model
{
    /** @use HasFactory<\Database\Factories\LeaveTypeFactory> */
    use HasFactory, HasUlids, SoftDeletes;

    /*
    |-------------------------------------
    | Configuration
    |-------------------------------------
    */
    protected $fillable = [
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
