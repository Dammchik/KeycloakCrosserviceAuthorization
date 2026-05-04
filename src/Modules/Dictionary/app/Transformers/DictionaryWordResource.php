<?php

namespace Modules\Dictionary\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class DictionaryWordResource extends JsonResource
{
    public function toArray($request): array
    {
        $attributeList = parent::toArray($request);

        Arr::forget($attributeList, [
            'id',
            'created_at',
            'updated_at',
        ]);

        return [
            ...$attributeList,
            'word' => $this->word->value,
            'dictionary' => new DictionaryShortResource($this->dictionary),
            'articles' => ArticleResource::collection($this->articles),
        ];
    }
}
