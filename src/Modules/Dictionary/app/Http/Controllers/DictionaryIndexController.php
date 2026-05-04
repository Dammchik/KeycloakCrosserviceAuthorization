<?php

namespace Modules\Dictionary\Http\Controllers;

use App\Http\Controllers\IndexController;
use Closure;
use Modules\Dictionary\Http\Requests\DictionaryIndexRequest;
use Modules\Dictionary\Models\Dictionary;
use Modules\Dictionary\Transformers\DictionaryResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class DictionaryIndexController extends IndexController
{
    /** @var string Класс модели, с которой работает контроллер */
    const string MODEL_CLASS = Dictionary::class;

    /** @var string Класс ресурса по умолчанию для преобразования ответов */
    const string DEFAULT_RESOURCE_CLASS = DictionaryResource::class;

    /** @var array<string> Список полей, по которым разрешена сортировка в индексе */
    const array AVAILABLE_SORT_LIST = [
        'id',
        'name',
        'short_name',
        'original_language',
        'publication_years',
        'dictionary_version',
        'module_number',
        'created_at',
    ];

    /**
     * Возвращает ассоциативный массив правил фильтрации для полей запроса.
     *
     * @return array<string, Closure>
     */
    public function getFilterTransform(): array
    {
        return [
            'name' => fn($query, $value) => $query->whereLike('name', "%{$value}%"),
            'short_name' => fn($query, $value) => $query->whereLike('short_name', "%{$value}%"),
            'original_language' => fn($query, $value) => $query->whereLike('original_language', "%{$value}%"),
            'publication_years' => fn($query, $value) => $query->whereLike('publication_years', "%{$value}%"),
            'dictionary_version' => fn($query, $value) => $query->whereLike('dictionary_version', "%{$value}%"),
            'module_number' => fn($query, $value) => $query->whereLike('module_number', "%{$value}%"),
        ];
    }

    /**
     * Обрабатывает HTTP-запрос на получение списка словарей.
     *
     * @param DictionaryIndexRequest $request
     * @return JsonResponse
     */
    public function __invoke(DictionaryIndexRequest $request): JsonResponse
    {
        return $this->handle($request);
    }
}
