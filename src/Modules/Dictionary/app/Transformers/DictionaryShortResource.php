<?php

namespace Modules\Dictionary\Transformers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class DictionaryShortResource extends DictionaryResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return Arr::only(
            parent::toArray($request),
            [
                'id',
                'name',
                'alias',
                'authors',
                'editors',
                'publication_years'
            ]
        );
    }
}
