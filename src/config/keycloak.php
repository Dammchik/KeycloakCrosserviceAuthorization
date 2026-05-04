<?php

return [
    'base_url' => env('KEYCLOAK_BASE_URL'),
    'realm'    => env('KEYCLOAK_REALM'),
    'client_id' => env('KEYCLOAK_CLIENT_ID'),
    'jwks_url' => env('KEYCLOAK_BASE_URL') . '/realms/' . env('KEYCLOAK_REALM') . '/protocol/openid-connect/certs',
    'issuer' => 'http://nsf-keycloak:8080/realms/myrealm',
];
