<?php

namespace Modules\Dictionary\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Dictionary\Models\Dictionary;
use Throwable;

class DictionaryService
{
    private $data;

    /** @var Dictionary Модель словаря */
    private Dictionary $model;

    /**
     * Конструктор сервиса.
     *
     * @param $data
     * @param Dictionary|null $model Существующая модель при обновлении, null — при создании
     */
    public function __construct($data, ?Dictionary $model = null)
    {
        $this->data = $data;
        $this->model = $model ?? new Dictionary();
    }

    /**
     * Запускает работу сервиса.
     *
     * @return Dictionary
     *
     * @throws Throwable
     */
    public function handle(): Dictionary
    {
        DB::beginTransaction();

        try {
            $modelData = Arr::only($this->data, $this->model->getFillable());

            $this->model->fill($modelData);
            $this->model->save();

            if (array_key_exists('authors', $this->data)) {
                $this->syncRelationByName('dictionaryAuthors', $this->data['authors'], 'name');
            }

            if (array_key_exists('responsible_editors', $this->data)) {
                $this->syncRelationByName('dictionaryEditors', $this->data['responsible_editors'], 'name');
            }

            $this->model->refresh();
            DB::commit();

            return $this->model;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function syncRelationByName(string $relation, array $data, string $fieldName): void
    {
        $newNames = collect($data)->pluck($fieldName)->all();

        $this->model->{$relation}()->whereNotIn($fieldName, $newNames)->delete();

        foreach ($newNames as $name) {
            $this->model->{$relation}()->firstOrCreate([$fieldName => $name]);
        }
    }
}
