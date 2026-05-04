<?php

namespace Modules\Dictionary\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Dictionary\Enums\DictionarySectionEnum;

class DictionaryRequest extends FormRequest
{
    public function isCreate(): bool
    {
        return $this->isMethod('POST');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'bail',
                when($this->isCreate(), 'required'),
                'string',
                'max:1056',
            ],
            'short_name' => [
                'bail',
                when($this->isCreate(), 'required'),
                'string',
                'max:255',
            ],
            'original_language' => [
                'bail',
                when($this->isCreate(), 'required'),
                'string',
                'max:100',
            ],
            'authors' => [
                'bail',
                when($this->isCreate(), 'required'),
                'array',
                'min:1',
            ],
            'authors.*.name' => [
                'bail',
                'required',
                'string',
                'max:512',
            ],
            'main_editor' => [
                'nullable',
                'string',
                'max:100',
            ],
            'responsible_editors' => [
                'nullable',
                'array',
            ],
            'responsible_editors.*.name' => [
                'bail',
                'required_with:responsible_editors',
                'string',
                'max:512',
            ],
            'publication_years' => [
                'bail',
                when($this->isCreate(), 'required'),
                'string',
                'max:100',
            ],
            'publishing_house' => [
                'nullable',
                'string',
                'max:255',
            ],
            'publication_city' => [
                'nullable',
                'string',
                'max:100',
            ],
            'dictionary_version' => [
                'bail',
                when($this->isCreate(), 'required'),
                'string',
                'max:100',
            ],
            'module_number' => [
                'bail',
                when($this->isCreate(), 'required'),
                'integer',
                Rule::in(DictionarySectionEnum::values()),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Наименование словаря обязательно к заполнению',
            'short_name.required' => 'Краткое наименование словаря обязательно к заполнению',
            'original_language.required' => 'Поле язык оригинала словаря обязательно к заполнению',
            'authors.required' => 'Авторы словаря обязательно к заполнению',
            'publication_years.required' => 'Год(ы) публикации словаря обязательно к заполнению',
            'dictionary_version.required' => 'Версия словаря обязательно к заполнению',
            'module_number.required' => 'Поле принадлежность к модулю обязательно к заполнению',
        ];
    }
}
