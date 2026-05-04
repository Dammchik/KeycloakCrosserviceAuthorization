<?php

namespace Modules\ApiGateway\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\ApiGateway\Services\GatewayService;
use Modules\ApiGateway\Services\ServiceRegistry;
use Prometheus\CollectorRegistry;

class ApiGatewayMiddleware
{
    protected GatewayService $gatewayService;
    protected ServiceRegistry $registry;

    public function __construct(
        GatewayService $gatewayService,
        ServiceRegistry $registry
    ) {
        $this->gatewayService = $gatewayService;
        $this->registry = $registry;
    }

    public function handle(Request $request, Closure $next)
    {
        $registry = app(CollectorRegistry::class);

        $start = microtime(true);

        // определяем сервис
        $service = $this->gatewayService->resolveService($request);

        if (!$service) {
            $this->recordMetric($registry, 'unknown', 404, 0);
            abort(404, 'Service not specified');
        }

        // проверяем существует ли сервис
        if (!$this->registry->serviceExists($service)) {
            $this->recordMetric($registry, $service, 404, 0);
            abort(404, 'Service not found');
        }

        // добавляем сервис в request
        $request->attributes->set('gateway_service', $service);

        $response = $next($request);

        $duration = microtime(true) - $start;

        $this->recordMetric(
            $registry,
            $service,
            $response->getStatusCode(),
            $duration
        );

        return $response;
    }

    protected function recordMetric($registry, $service, $status, $duration)
    {
        $counter = $registry->getOrRegisterCounter(
            'api_gateway',
            'requests_total',
            'Total API Gateway requests',
            ['service', 'status']
        );

        $counter->inc([
            $service,
            (string) $status
        ]);

        $histogram = $registry->getOrRegisterHistogram(
            'api_gateway',
            'request_duration_seconds',
            'API Gateway request duration',
            ['service']
        );

        $histogram->observe($duration, [$service]);
    }
}
