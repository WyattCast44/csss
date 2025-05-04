<?php

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\File;

test('morph map is set for all models', function () {
    $map = Relation::morphMap();

    $discoveredModels = [];

    // search the namespace for models
    $files = File::glob(app_path('Models').'/*.php');

    foreach ($files as $file) {
        $discoveredModels[] = 'App\Models\\'.basename($file, '.php');
    }

    foreach ($discoveredModels as $model) {
        expect(in_array($model, array_values($map)))->toBeTrue();
    }
});
