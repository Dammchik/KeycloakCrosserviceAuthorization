<?php

namespace App\Http\Controllers;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;

class MetricsController
{
    public function __invoke()
    {
        $registry = app(CollectorRegistry::class);

        $renderer = new RenderTextFormat();
        $result = $renderer->render($registry->getMetricFamilySamples());

        return response($result)
            ->header('Content-Type', RenderTextFormat::MIME_TYPE);
    }
}
