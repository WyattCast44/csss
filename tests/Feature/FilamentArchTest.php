<?php

arch()
    ->expect('App\Filament\App\Widgets')
    ->toBeClasses()
    ->toHaveSuffix('Widget');