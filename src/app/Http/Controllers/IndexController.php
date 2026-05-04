<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class IndexController extends Controller
{
    const string MODEL_CLASS = '';
    const string DEFAULT_RESOURCE_CLASS = '';
    const string DEFAULT_MESSAGE = 'Список сущностей.';
    const bool NEEDS_PAGINATION_DATA = true;
    const int DEFAULT_PER_PAGE = 10;
    const int MAX_PER_PAGE = 100;
    const string DEFAULT_SORT = 'id';
    const array AVAILABLE_SORT_LIST = ['id'];
    public array $filtersWithArrayListValues = [];

    public function sendResponse(array $result = [], ?string $message = null, int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message ?? static::DEFAULT_MESSAGE,
            ...$result,
        ];

        return response()->json($response, $code, options: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function getPaginatedCollectionData(Builder $query): array
    {
        $paginator = $this->getPaginator(request(), $query);
        $resourceClass = request()->resourceClassName ?? static::DEFAULT_RESOURCE_CLASS;

        $data = [
            'data' => str_is_empty($resourceClass) ? $paginator->items() : $resourceClass::collection($paginator->items()),
        ];

        if (request()->get('needsPagination', static::NEEDS_PAGINATION_DATA)) {
            $data['pagination'] = $this->getPaginationData($paginator);
        }

        return $data;
    }

    public function getCollectionData(Builder $query): array
    {
        $entityList = $query->get();
        $resourceClass = request()->resourceClassName ?? static::DEFAULT_RESOURCE_CLASS;

        return [
            'data' => str_is_empty($resourceClass) ? $entityList : $resourceClass::collection($entityList),
        ];
    }

    public function getQuery(array $filterList): Builder
    {
        $query = static::MODEL_CLASS::query();

        foreach ($filterList as $name => $value) {
            $transform = Arr::get($this->getFilterTransform(), $name);
            if (!var_is_null($transform)) {
                $query = $this->getFilterTransform()[$name]($query, $value);
            }
        }

        return $this->setSort($query);
    }

    protected function getPaginationData(Paginator $paginator): array
    {
        return [
            'current' => $paginator->currentPage(),
            'per-page' => $paginator->perPage(),
            'total' => $paginator->lastPage(),
            'items' => [
                'count' => $paginator->count(),
                'total' => $paginator->total(),
            ]
        ];
    }

    protected function getPaginator(Request $request, Builder $query): Paginator
    {
        $perPage = $request->query('per-page', static::DEFAULT_PER_PAGE);

        if ($perPage > static::MAX_PER_PAGE) {
            $perPage = static::MAX_PER_PAGE;
        }

        $page = $request->query('page', 1);
        $count = $query->count();
        $lastPage = ceil($count / $perPage);

        if ($lastPage <= 0) {
            $lastPage = 1;
        }

        if ($page > 1 && $lastPage < $page) {
            $page = $lastPage;
        }

        return $query->paginate($perPage, page: $page);
    }

    public function getFilters(FormRequest $request): array
    {
        $rawFilters = $request->validated();
        $ignoredFilters = $request->get('ignoredFilters', []);
        $forcedFilters = $request->get('forcedFilters', []);

        $arrayFilters = Arr::only($rawFilters, $this->filtersWithArrayListValues);
        $dotFilters = Arr::except($rawFilters, $this->filtersWithArrayListValues);
        $dotFilters = Arr::dot($dotFilters);

        $filterList = [
            ...Arr::except($dotFilters, $ignoredFilters),
            ...Arr::dot($forcedFilters),
            ...$arrayFilters
        ];

        $requestFilterList = $this->transformFilterValues($filterList);
        request()->merge(['filterList' => $requestFilterList]);

        return $requestFilterList;
    }

    protected function transformFilterValues(array $filterList): array
    {
        return $filterList;
    }

    public function getFilterTransform(): array
    {
        return [];
    }

    public function setSort(Builder $query): Builder
    {
        $sort = request()->forcedSort ?? request()->query('sort', static::DEFAULT_SORT);
        $direction = 'asc';

        if (var_is_string($sort) && Str::startsWith($sort, '-')) {
            $direction = 'desc';
            $sort = substr($sort, 1);
        }

        if (!var_is_string($sort) || !in_array($sort, static::AVAILABLE_SORT_LIST)) {
            return $query;
        }

        return $query->orderBy($sort, $direction);
    }

    protected function handle(FormRequest $request): JsonResponse
    {
        $query = $this->getQuery($this->getFilters($request));

        return $this->sendResponse(
            $request->needsPagination ?? static::NEEDS_PAGINATION_DATA
                ? $this->getPaginatedCollectionData($query)
                : $this->getCollectionData($query)
            ,
            static::DEFAULT_MESSAGE,
        );
    }
}
