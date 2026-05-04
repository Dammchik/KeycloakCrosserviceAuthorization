<?php

namespace Modules\Dictionary\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RestTraits\DefaultShowTrait;
use Modules\Dictionary\Models\Word;
use Modules\Dictionary\Transformers\WordResource;

class WordController extends Controller
{
    use DefaultShowTrait;

    const string MODEL_CLASS = Word::class;
    const string DEFAULT_RESOURCE_CLASS = WordResource::class;

}
