<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function jwt_generate(array $payload): string
{
    $secret = (string)env('JWT_SECRET');
    if ($secret === '') {
        // Development fallback to avoid hard failure during local setup.
        $secret = ENVIRONMENT === 'production' ? '' : 'dev_fallback_secret_change_me';
    }

    if ($secret === '') {
        throw new \RuntimeException('JWT_SECRET is not configured.');
    }

    $expire = (int)env('JWT_EXPIRE', 86400);

    $payload['iat'] = time();
    $payload['exp'] = time() + $expire;

    return JWT::encode($payload, $secret, 'HS256');
}

function jwt_verify(string $token): object|false
{
    try {
        $secret = (string)env('JWT_SECRET');
        if ($secret === '') {
            $secret = ENVIRONMENT === 'production' ? '' : 'dev_fallback_secret_change_me';
        }

        if ($secret === '') {
            return false;
        }

        return JWT::decode($token, new Key($secret, 'HS256'));
    } catch (\Exception $e) {
        return false;
    }
}
