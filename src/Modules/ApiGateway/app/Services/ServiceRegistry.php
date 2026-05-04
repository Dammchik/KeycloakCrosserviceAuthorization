<?php

namespace Modules\ApiGateway\Services;

class ServiceRegistry
{
    protected array $services;

    public function __construct()
    {
        $this->services = config('apigateway.services', []);
    }

    public function getService(string $name): ?array
    {
        return $this->services[$name] ?? null;
    }

    public function serviceExists(string $name): bool
    {
        return isset($this->services[$name]);
    }

    public function all(): array
    {
        return $this->services;
    }
}
