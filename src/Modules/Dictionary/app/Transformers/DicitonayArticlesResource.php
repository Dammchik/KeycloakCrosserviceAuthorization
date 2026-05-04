<?php

namespace Modules\Dictionary\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class DicitonayArticlesResource extends JsonResource
{
    public function toArray($request): array
    {
        $attributeList = parent::toArray($request);

        $dictionary_id = Arr::only($attributeList, [
            'dictionary_id',
        ]);

        return [
            ...$dictionary_id,
            'dictionary' => new DictionaryShortResource($this->dictionary),
            'articles' => ArticleResource::collection($this->articles),
        ];
    }
}
