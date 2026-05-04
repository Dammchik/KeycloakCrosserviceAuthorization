<?php

namespace Modules\Dictionary\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RestTraits\DefaultDestroyTrait;
use App\Http\Controllers\RestTraits\DefaultShowTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Modules\Dictionary\Http\Requests\DictionaryRequest;
use Modules\Dictionary\Models\Dictionary;
use Modules\Dictionary\Services\DictionaryService;
use Modules\Dictionary\Services\ImportDictionaryArticlesService;
use Modules\Dictionary\Transformers\DictionaryResource;
use Throwable;

class DictionaryController extends Controller
{
    use DefaultShowTrait;
    use DefaultDestroyTrait;

    /** @var string Класс модели, с которой работает контроллер */
    const string MODEL_CLASS = Dictionary::class;

    /** @var string Класс ресурса по умолчанию для преобразования ответов */
    const string DEFAULT_RESOURCE_CLASS = DictionaryResource::class;

    /**
     * Создаёт новый словарь на основе валидированных данных запроса.
     *
     * @param DictionaryRequest $request
     *
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function store(DictionaryRequest $request): JsonResponse
    {
        return $this->oldBaseAction(
            action: fn() => new DictionaryService($request->validated())->handle(),
            request: $request,
            actionName: 'store',
        );
    }

    /**
     * Обновляет существующий словарь частично или полностью.
     *
     * @param DictionaryRequest $request
     *
     * @return JsonResponse
     */
    public function update(DictionaryRequest $request): JsonResponse
    {
        return $this->oldBaseActionWithModel(
            action: fn() => new DictionaryService(
                $request->validated(),
                $request->model
            )->handle(),
            actionName: 'update',
            request: $request,
        );
    }

    /**
     * Обновляет только поле 'alias' у существующего словаря.
     *
     * @return JsonResponse
     */
    public function alias(): JsonResponse
    {
        return $this->oldBaseActionWithModel(
            action: function (Model $model, $request, $validate, $rules) {
                request()->validate($rules);
                $model->alias = $request->input("alias");

                $model->save();

                return $model;
            },
            rules: ['alias' => ['required', 'string', 'min:1', 'max:30']],
        );
    }

    /**
     * Импортирует статьи в указанный словарь.
     *
     * @return JsonResponse
     */
    public function importArticle(): JsonResponse
    {
        (new ImportDictionaryArticlesService(request()->all(), request()->model))->handle();

        return $this->sendResponse();
    }
}
