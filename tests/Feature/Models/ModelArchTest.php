<?php

use App\Models\InboundUserInprocessingAction;
use App\Models\User;
use App\Support\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

arch()
    ->expect('App\Models')
    ->toBeClasses();

arch()
    ->expect('App\Models')
    ->toExtend(Model::class)
    ->ignoring(User::class)
    ->ignoring(InboundUserInprocessingAction::class);

arch()
    ->expect('App\Models')
    ->toUseTrait(HasUlids::class)
    ->ignoring(InboundUserInprocessingAction::class);

arch()
    ->expect('App\Models')
    ->toUseTrait(SoftDeletes::class)
    ->ignoring(InboundUserInprocessingAction::class);
