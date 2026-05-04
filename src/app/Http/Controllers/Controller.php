<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class Controller
{
    const string MODEL_CLASS = '';
    const string DEFAULT_RESOURCE_CLASS = '';
    const string DEFAULT_MESSAGE = 'Запрос успешно выполнен';
    /**
     * @var array $relations Массив связанных сущностей
     */
    public static array $relationsData = [];
    public static string $modelIdRouteName = 'id';
    public static array $exceptedRouteParamList = [];

    public static function getModelClass(): string
    {
        return static::MODEL_CLASS;
    }

    public function actionResourceClassMap(): array
    {
        return [];
    }

    public function actionMessageMap(): array
    {
        return [
            'store' => 'Сущность успешно создана.',
            'show' => 'Данные сущности.',
            'update' => 'Сущность успешно обновлена.',
            'destroy' => 'Сущность удалена.',
        ];
    }

    public static function getActionModelData(string $routeName): array
    {
        return [];
    }

    public function getResourceClass(string $action): string
    {
        return request()->resourceClassName ?? $this->actionResourceClassMap()[$action] ?? static::DEFAULT_RESOURCE_CLASS;
    }

    public function getMessage(string $action): string
    {
        return $this->actionMessageMap()[$action] ?? static::DEFAULT_MESSAGE;
    }

    public function sendResponse(array $result = [], ?string $message = null, int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message ?? static::DEFAULT_MESSAGE,
            ...$result,
        ];

        return response()->json($response, $code, options: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function getEntityData(Model $model, string $action): array
    {
        $resourceClass = $this->getResourceClass($action);

        return ['data' => str_is_empty($resourceClass) ? $model->withoutRelations() : new ($resourceClass)($model)];
    }

    public static function getModelIdValueFromRoute()
    {
        return request()->route()->parameter(static::$modelIdRouteName);
    }

    public static function getModelprimaryKeyName(): string
    {
        /** @var Model $modelClass */
        $modelClass = static::getModelClass();

        return (new $modelClass())->getKeyName();
    }

    public static function find()
    {
        $actionName = request()->routeNameWithPostfix ?? '';

        /** @var Model $modelClass */
        ['primaryKey' => $primaryKey, 'modelClass' => $modelClass] = array_merge(
            [
                'primaryKey' => static::getModelprimaryKeyName(),
                'modelClass' => static::getModelClass(),
            ],
            static::getActionModelData($actionName) ?? []
        );

        return $modelClass::query()
            ->where($primaryKey, static::getModelIdValueFromRoute())
            ->first();
    }

    /**
     * @return Model
     */
    public static function findOrAbort(): Model
    {
        return request()->model
            ?? static::find()
            ?? throw new NotFoundHttpException('Сущность не найдена.');
    }

    /**
     * @return Model
     */
    public static function findNestedOrAbort(): Model
    {
        $parameters = request()->route()->parameters();
        $model = static::findOrAbort();
        $currentModel = $model;

        $parameters = Arr::except($parameters, [static::$modelIdRouteName, ...static::$exceptedRouteParamList]);

        foreach (array_reverse($parameters) as $parameter => $value) {
            $relationsData = [
                'id' => 'id',
                'idIsNumeric' => true,
                ...Arr::get(
                    static::$relationsData,
                    $parameter,
                    ['name' => Str::substr($parameter, 0, -2)]
                ),
            ];

            ['name' => $name, 'id' => $id, 'idIsNumeric' => $idIsNumeric] = $relationsData;
            $value = $idIsNumeric ? (int) $value : $value;
            $parentModel = $currentModel->$name;

            if ($parentModel === null || $parentModel->$id !== $value) {
                throw new NotFoundHttpException('Сущность не найдена.');
            }

            $currentModel = $parentModel;
        }

        return $model;
    }

    /**
     * @deprecated
     *
     * @param  callable(?Request): ?Model  $action
     * @param  Request|null  $request
     * @param  string  $actionName
     * @param  int  $code
     * @param  bool  $validate
     * @param  array  $rules
     * @return JsonResponse
     */
    protected function oldBaseAction(
        callable $action,
        mixed $request = null,
        string $actionName = '',
        int $code = 200,
        bool $validate = false,
        array $rules = [],
    ): JsonResponse {
        /** @var Model $model */
        $model = $action($request, $validate, $rules);

        return $this->sendResponse(
            result: var_is_null($model)
                ? []
                : $this->getEntityData($model, $actionName),
            message: $this->getMessage($actionName),
            code: $code
        );
    }

    /**
     * @deprecated
     *
     * @param  callable(Model, ?Request): ?Model|null  $action
     * @param  mixed|null  $model
     * @param  string  $actionName
     * @param  mixed|null  $request
     * @param  int  $code
     * @param  bool  $validate
     * @param  array  $rules
     * @return JsonResponse
     */
    protected function oldBaseActionWithModel(
        ?callable $action = null,
        mixed $model = null,
        ?string $actionName = null,
        mixed $request = null,
        int $code = 200,
        bool $validate = false,
        array $rules = [],
    ): JsonResponse {
        if (var_is_null($actionName)) {
            $actionName = request()->routeNameWithPostfix;
        }

        if (var_is_null($action)) {
            $action = fn($model, $request, $validate, $rules) => $model;
        }

        if (var_is_null($model)) {
            $model = static::findOrAbort();
        }

        $resultModel = $action($model, $request ?? request(), $validate, $rules, $actionName);

        return $this->sendResponse(
            result: var_is_null($resultModel)
                ? []
                : $this->getEntityData($resultModel, $actionName),
            message: $this->getMessage($actionName),
            code: $code
        );
    }

    protected function getRequestData(
        mixed $request = null,
        bool $validate = true,
        array $rules = [],
    ) {
        return $validate
            ? (
            array_is_empty($rules)
                ? $request->validated()
                : $request->validate($rules)
            )
            : $request->all();
    }

    protected function getStoreDefaultParams(): array
    {
        return [];
    }

    protected function getStoreForcedParams(): array
    {
        return [];
    }

    protected function getStoreParams(
        mixed $request = null,
        bool $validate = true,
        array $rules = [],
        string $actionName = 'store'
    ) {
        return $this->getActionParams(
            actionName: $actionName,
            defaultName: 'store',
            request: $request,
            validate: $validate,
            rules: $rules
        );
    }

    protected function baseStoreAction(
        ?callable $action = null,
        mixed $request = null,
        bool $validate = true,
        array $rules = [],
    ) {
        if (var_is_null($request)) {
            $request = request();
        }

        return $this->oldBaseAction(
            action: $action ??
            fn($request, $validate, $rules) => static::getModelClass()::create(
                $this->getStoreParams($request, $validate, $rules)
            ),
            request: $request,
            actionName: 'store',
            code: 201,
            validate: $validate,
            rules: $rules
        );
    }


    protected function getUpdateDefaultParams(): array
    {
        return [];
    }

    protected function getUpdateForcedParams(): array
    {
        return [];
    }

    protected function getMethodName(
        string $actionName,
        string $defaultName = '',
        string $prefix = '',
        string $postfix = ''
    ): string {
        $actionNameStr = ucfirst($actionName);
        $methodName = $prefix.$actionNameStr.$postfix;

        if ($actionName !== $defaultName) {
            $controllerClassName = get_class($this);

            if (!method_exists($controllerClassName, $methodName)) {
                $methodName = $prefix.ucfirst($defaultName).$postfix;
            }
        }

        return $methodName;
    }

    protected function getActionParams(
        string $actionName,
        string $defaultName = '',
        mixed $request = null,
        bool $validate = true,
        array $rules = [],
    ) {
        $getDefaultParamsMethodName = $this->getMethodName(
            actionName: $actionName,
            defaultName: $defaultName,
            prefix: 'get',
            postfix: 'DefaultParams'
        );
        $getForcedParamsMethodName = $this->getMethodName(
            actionName: $actionName,
            defaultName: $defaultName,
            prefix: 'get',
            postfix: 'ForcedParams'
        );

        return [
            ...$this->$getDefaultParamsMethodName(),
            ...$this->getRequestData($request, $validate, $rules),
            ...$this->$getForcedParamsMethodName(),
        ];
    }

    protected function getUpdateParams(
        mixed $request = null,
        bool $validate = true,
        array $rules = [],
        string $actionName = 'update'
    ) {
        return $this->getActionParams(
            actionName: $actionName,
            defaultName: 'update',
            request: $request,
            validate: $validate,
            rules: $rules
        );
    }

    protected function baseUpdateAction(
        ?string $actionName = null,
        ?callable $action = null,
        mixed $request = null,
        bool $validate = true,
        array $rules = [],
    ) {
        if (var_is_null($request)) {
            $request = request();
        }

        return $this->oldBaseActionWithModel(
            action: $action ??
            function ($model, $request, $validate, $rules, $actionName) {
                $model->fill(
                    $this->getUpdateParams($request, $validate, $rules, $actionName)
                );
                $model->save();
                $model->refresh();

                return $model;
            },
            actionName: $actionName ?? 'update',
            request: $request,
            validate: $validate,
            rules: $rules
        );
    }

}
