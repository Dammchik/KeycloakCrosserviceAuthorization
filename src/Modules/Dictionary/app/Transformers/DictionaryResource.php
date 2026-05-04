<?php

namespace Modules\Dictionary\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Dictionary\Models\Dictionary;

class DictionaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        /** @var Dictionary $this */
        $attributes = $this->attributesToArray();

        return [
          ...$attributes,
          'authors' => $this->getAuthorNames(),
          'editors' => $this->getEditorNames(),
        ];
    }
}
