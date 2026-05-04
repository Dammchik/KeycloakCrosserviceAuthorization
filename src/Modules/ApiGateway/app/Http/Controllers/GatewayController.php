<?php
//
//namespace Modules\ApiGateway\Http\Controllers;
//
//use Illuminate\Http\Request;
//use Illuminate\Routing\Controller;
//use Illuminate\Support\Facades\Http;
//use Symfony\Component\HttpFoundation\Response;
//
//class GatewayController extends Controller
//{
//    /**
//     * Главная точка входа для всех запросов.
//     */
//    public function handle(Request $request)
//    {
//
//        $service = $this->resolveService($request);
//
//        $this->authorizeService($service);
//
//        $targetUrl = $this->buildTargetUrl($request, $service);
//
//        return $this->forwardRequest($request, $targetUrl);
//    }
//
//    /**
//     * Определяет, к какому сервису относится запрос.
//     */
//    private function resolveService(Request $request): string
//    {
//        $path = $request->path();
//
//        if (str_contains($path, 'dictionary')) {
//            return 'dictionary';
//        }
//
//        abort(Response::HTTP_NOT_FOUND, 'Service not found');
//    }
//
//    /**
//     * Проверяет, разрешён ли доступ к сервису.
//     * (расширяется позже для ролей / client_id и т.д.)
//     */
//    private function authorizeService(string $service): void
//    {
//        $allowedServices = ['dictionary'];
//
//        if (!in_array($service, $allowedServices)) {
//            abort(Response::HTTP_FORBIDDEN, 'Access to service denied');
//        }
//    }
//
//    /**
//     * Формирует внутренний URL сервиса.
//     */
//    private function buildTargetUrl(Request $request, string $service): string
//    {
//        $baseUrls = [
//            'dictionary' => config('services.dictionary.url'),
//        ];
//
//        $baseUrl = $baseUrls[$service] ?? null;
//
//        if (!$baseUrl) {
//            abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Service URL not configured');
//        }
//
//        $cleanPath = preg_replace('#^api/#', '', $request->path());
//
//        return rtrim($baseUrl, '/') . '/' . $cleanPath;
//    }
//
//    /**
//     * Пересылает запрос во внутренний сервис.
//     */
//    private function forwardRequest(Request $request, string $targetUrl)
//    {
//        $response = Http::withHeaders(
//            $this->buildHeaders($request)
//        )->send(
//            $request->method(),
//            $targetUrl,
//            [
//                'query' => $request->query(),
//                'body'  => $request->getContent(),
//            ]
//        );
//
//        return response(
//            $response->body(),
//            $response->status()
//        )->withHeaders($response->headers());
//    }
//
//    /**
//     * Формирует внутренние заголовки для доверенного запроса.
//     */
//    private function buildHeaders(Request $request): array
//    {
//        return [
//            'Authorization'       => $request->header('Authorization'),
//            'X-Internal-Gateway'  => 'true',
//            'Accept'              => 'application/json',
//            'Content-Type'        => $request->header('Content-Type', 'application/json'),
//        ];
//    }
//}
