<?php

namespace Modules\Auth\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use Illuminate\Support\Facades\Cache;

class KeycloakJwtValidator
{
    public function validate(string $token): array
    {
        $jwks = Cache::remember(
            'keycloak.jwks',
            3600,
            fn () => json_decode(
                file_get_contents(config('keycloak.jwks_url')),
                true
            )
        );

        $decoded = JWT::decode($token, JWK::parseKeySet($jwks));

        $payload = (array) $decoded;

        $this->assertIssuer($payload);
        $this->assertAudience($payload);
        $this->assertNotExpired($payload);

        return $payload;
    }

    protected function assertIssuer(array $payload): void
    {
        $allowedIssuers = [
            'http://localhost:8080/realms/myrealm',
            'http://nsf-keycloak:8080/realms/myrealm',
        ];

        if (!in_array($payload['iss'] ?? null, $allowedIssuers, true)) {
            abort(401, 'Invalid token issuer');
        }
    }


    protected function assertAudience(array $payload): void
    {
        if (($payload['azp'] ?? null) !== config('keycloak.client_id')) {
            abort(401, 'Invalid authorized party');
        }
    }

    protected function assertNotExpired(array $payload): void
    {
        if (($payload['exp'] ?? 0) < time()) {
            abort(401, 'Token expired');
        }
    }
}
