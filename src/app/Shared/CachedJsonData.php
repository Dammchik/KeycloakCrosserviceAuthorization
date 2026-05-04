<?php

namespace App\Shared;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;

trait CachedJsonData
{
//    const NEEDS_CACHE = false;
//    const EXPIRE = 60;

    public function fetch(): mixed
    {
        return null;
    }

    public function getCacheKey(?string $transformKey = null): string
    {
        return static::class . ($transformKey ? "_{$transformKey}" : '');
    }

    public function isValidFormat($data): bool
    {
        return true;
    }

    public function validate(mixed $data): void
    {
        if ($this->isValidFormat($data)) {
            throw new \Error('Полученные данные не соответствуют ожидаемому формату данных');
        }
    }

    protected function transform(mixed $data)
    {
        return $data;
    }

    protected function fetchAndValidate($validate = true): mixed
    {
        $data = $this->fetch();

        if ($validate) {
            $this->validate($data);
        }

        return $data;
    }

    /**
     * @param      $transformKey
     * @param      $key
     * @param bool $validate
     *
     * @return array|mixed
     */
    public function set($transformKey, $key, bool $validate = true): mixed
    {
        $data = $this->fetchAndValidate($validate);

        Redis::set($this->getCacheKey(), json_encode($data, JSON_UNESCAPED_UNICODE), 'EX', static::EXPIRE);

        if (!var_is_null($transformKey)) {
            $transformData = $this->transform($data);

            foreach ($transformData as $transformedKey => $transformedData) {
                Redis::set($this->getCacheKey($transformedKey), json_encode($transformedData, JSON_UNESCAPED_UNICODE), 'EX', static::EXPIRE);
            }

            return Arr::get($transformData, $transformKey);
        }

        return $data;
    }

    public function get(bool $validate = true, ?string $transformKey = null): mixed
    {
        if (!env('REDIS_USE', false) || !static::NEEDS_CACHE) {
            $data = $this->fetchAndValidate($validate);

            if (var_is_null($transformKey)) {
                return $data;
            }

            $transformData = $this->transform($data);

            return Arr::get($transformData, $transformKey);
        }

        $key = $this->getCacheKey($transformKey);

        if (Redis::exists($key)) {
            return json_decode(Redis::get($key), true);
        }

        return $this->set($transformKey, $key);
    }
}
