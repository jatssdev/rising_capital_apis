<?php
// config/jwt.php
function base64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data)
{
    return base64_decode(strtr($data, '-_', '+/'));
}

function generate_jwt($user_id)
{
    $secret_key = 'dilniraanibharaugharnapaani'; // Change this to a secure key
    $issued_at = time();
    $expiration_time = $issued_at + 60 * 60; // Token valid for 1 minute (60 seconds)
    $payload = array(
        'iss' => 'http://localhost/rising', // Issuer
        'iat' => $issued_at,        // Issued at
        'exp' => $expiration_time,  // Expiration time
        'user_id' => $user_id       // Store user identifier
    );

    $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
    $base64_header = base64url_encode($header);
    $base64_payload = base64url_encode(json_encode($payload));
    $signature = hash_hmac('sha256', $base64_header . '.' . $base64_payload, $secret_key, true);
    $base64_signature = base64url_encode($signature);

    return $base64_header . '.' . $base64_payload . '.' . $base64_signature;
}

function validate_jwt($jwt)
{
    $secret_key = 'dilniraanibharaugharnapaani';
    $parts = explode('.', $jwt);

    if (count($parts) != 3) {
        echo 'Invalid token structure';
        return false; // Invalid token structure
    }

    $base64_header = $parts[0];
    $base64_payload = $parts[1];
    $base64_signature = $parts[2];

    // Decode payload
    $payload = json_decode(base64url_decode($base64_payload), true);
    $signature = base64url_decode($base64_signature);


    // Check if the token signature is valid
    $valid_signature = hash_hmac('sha256', $base64_header . '.' . $base64_payload, $secret_key, true);


    if ($signature !== $valid_signature) {

        return false; // Invalid signature
    }

    // Check if the token is expired
    if ($payload['exp'] < time()) {

        return false; // Token expired
    }

    return $payload['user_id']; // Return user_id if valid
}
