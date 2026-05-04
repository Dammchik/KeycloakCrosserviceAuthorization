<?php

namespace Modules\Dictionary\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Modules\Dictionary\Models\Article;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $attributeList = parent::toArray($request);

        Arr::forget($attributeList, [
            'dictionary_word_id',
            'content_cleared',
            'created_at',
            'updated_at',
        ]);

        /** @var Article $this */
        if ($this->is_link) {
            $attributeList['links'] = LinkResource::collection($this->links) ;
        } else {
            $attributeList['sections'] = SectionResource::collection($this->sections()->orderBy('number')->get()); ;
        }

        return $attributeList;
    }
}
