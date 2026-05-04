<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\Http;

class UserService
{
    protected string $host;
    protected string $realm;
    protected string $clientId;
    protected string $clientSecret;

    public function __construct()
    {
        $this->host = env('KEYCLOAK_HOST', 'http://keycloak:8080');
        $this->realm = env('KEYCLOAK_REALM', 'master');
        $this->clientId = env('KEYCLOAK_CLIENT_ID');
        $this->clientSecret = env('KEYCLOAK_CLIENT_SECRET');
    }

    private function adminToken(): ?string
    {
        $url = "{$this->host}/realms/{$this->realm}/protocol/openid-connect/token";

        $response = Http::asForm()->post($url, [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        if (!$response->ok()) {
            return null;
        }

        return $response->json()['access_token'];
    }

    public function getUserInfo(string $token)
    {
        $url = "{$this->host}/realms/{$this->realm}/protocol/openid-connect/userinfo";

        $response = Http::withToken($token)->get($url);

        if (!$response->ok()) {
            return ['error' => 'Invalid token'];
        }

        return $response->json();
    }

    public function getUsers()
    {
        $token = $this->adminToken();

        if (!$token) {
            return ['error' => 'Cannot get admin access token'];
        }

        $url = "{$this->host}/admin/realms/{$this->realm}/users";

        $response = Http::withToken($token)->get($url);

        if (!$response->ok()) {
            return ['error' => 'Cannot load users'];
        }

        return $response->json();
    }

    public function logout(string $refreshToken)
    {
        $url = "{$this->host}/realms/{$this->realm}/protocol/openid-connect/logout";

        $response = Http::asForm()->post($url, [
            'refresh_token' => $refreshToken,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        if (!$response->ok()) {
            return ['error' => 'Logout failed'];
        }

        return ['status' => 'logged_out'];
    }
}
