<?php

namespace Modules\Dictionary\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DictionaryIndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:1056'],
            'short_name' => ['nullable', 'string', 'max:255'],
            'original_language' => ['nullable', 'string', 'max:100'],
            'publication_years' => ['nullable', 'string', 'max:100'],
            'dictionary_version' => ['nullable', 'string', 'max:100'],
            'module_number' => ['nullable', 'integer'],
        ];
    }
}
