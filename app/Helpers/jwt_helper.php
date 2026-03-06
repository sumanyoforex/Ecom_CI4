<?php

/**
 * jwt_helper.php — Helper for creating and verifying JWT tokens.
 *
 * JWT (JSON Web Token) stores user info securely in a signed string.
 * Format:  header.payload.signature
 *
 * Usage:
 *   $token   = jwt_generate(['user_id' => 5]);
 *   $payload = jwt_verify($token);
 */

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Generate a JWT token for the given payload.
 *
 * @param  array $payload  Data to encode (e.g., user_id, role)
 * @return string          Signed JWT string
 */
function jwt_generate(array $payload): string
{
    $secret  = $_ENV['JWT_SECRET']  ?? 'fallback_secret';
    $expire  = $_ENV['JWT_EXPIRE']  ?? 86400; // 1 day in seconds

    $payload['iat'] = time();              // issued at
    $payload['exp'] = time() + $expire;   // expiry

    return JWT::encode($payload, $secret, 'HS256');
}

/**
 * Verify a JWT token and return its payload.
 *
 * @param  string $token  JWT string from cookie or header
 * @return object|false   Decoded payload or false if invalid
 */
function jwt_verify(string $token): object|false
{
    try {
        $secret = $_ENV['JWT_SECRET'] ?? 'fallback_secret';
        return JWT::decode($token, new Key($secret, 'HS256'));
    } catch (\Exception $e) {
        return false;
    }
}
