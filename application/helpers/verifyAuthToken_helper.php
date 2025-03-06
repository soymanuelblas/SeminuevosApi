<?php

if(!function_exists('verifyAuthToken')) {
    function verifyAuthToken($token) {
        $jwt = new JWT();
        $JwtSecret = getenv('SECRET_KEY');
        
        $verification = $jwt->decode($token, $JwtSecret, ['HS256']);

        if (isset($verification->exp) && $verification->exp < time()) {
            return false;
        }
        $verfication_json = $jwt->jsonEncode($verification);

        return $verfication_json;
    }
}