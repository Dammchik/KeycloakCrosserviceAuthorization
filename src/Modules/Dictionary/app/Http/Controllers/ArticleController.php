<?php

namespace Modules\Dictionary\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RestTraits\DefaultShowTrait;
use Modules\Dictionary\Models\Article;
use Modules\Dictionary\Transformers\ArticleResource;

class ArticleController extends Controller
{
    use DefaultShowTrait;

    const string MODEL_CLASS = Article::class;
    const string DEFAULT_RESOURCE_CLASS = ArticleResource::class;
}
