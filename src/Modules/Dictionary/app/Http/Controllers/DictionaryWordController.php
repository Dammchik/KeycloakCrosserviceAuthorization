<?php

namespace Modules\Dictionary\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RestTraits\DefaultShowTrait;
use Modules\Dictionary\Models\DictionaryWord;
use Modules\Dictionary\Transformers\DictionaryWordResource;

class DictionaryWordController extends Controller
{
    use DefaultShowTrait;

    const string MODEL_CLASS = DictionaryWord::class;
    const string DEFAULT_RESOURCE_CLASS = DictionaryWordResource::class;
}
