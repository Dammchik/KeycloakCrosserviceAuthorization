<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RestTraits\DefaultDestroyTrait;
use App\Http\Controllers\RestTraits\DefaultShowTrait;
use App\Http\Controllers\RestTraits\DefaultStoreTrait;
use App\Http\Controllers\RestTraits\DefaultUpdateTrait;

trait DefaultRestTrait
{
    use DefaultStoreTrait;
    use DefaultShowTrait;
    use DefaultUpdateTrait;
    use DefaultDestroyTrait;
}
