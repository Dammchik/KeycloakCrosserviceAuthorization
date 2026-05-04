<?php

namespace Modules\Dictionary\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Modules\Dictionary\Models\Link;
use Modules\Dictionary\Models\Word;

class LinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $attributeList = parent::toArray($request);

        Arr::forget($attributeList, [
            'article_id',
            'created_at',
            'updated_at',
        ]);

        /** @var Link $this */
        return [
            ...$attributeList,
            'word_id' => Word::query()->where('value', $this->word)->first()->id,
        ];
    }
}
