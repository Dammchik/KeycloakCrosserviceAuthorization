<?php

namespace Modules\Dictionary\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Modules\Dictionary\Models\Section;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $attributeList = Arr::only(parent::toArray($request), [
            'type',
            'sections',
            'number',
            'content'
        ]);

        $attributeList['sections'] = $this->sectionList();

        /** @var Section $this */
        return $attributeList;
    }
}
