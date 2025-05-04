<?php

namespace App\Support\Concerns;

use Illuminate\Database\Eloquent\Concerns\HasUlids as LaravelHasUlids;

trait HasUlids
{
    use LaravelHasUlids;

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return array
     */
    public function uniqueIds()
    {
        return ['ulid'];
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
