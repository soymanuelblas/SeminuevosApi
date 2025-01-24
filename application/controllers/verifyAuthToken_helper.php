<?php

if(!function_exists('verifyAuthToken')) {
    function verifyAuthToken($token) {
        $jwt = new JWT();
        $JwtSecret = getenv('SECRET_KEY');
        
        $verification = $jwt->decode($token, $JwtSecret, ['HS256']);

        $verfication_json = $jwt->json_encode($verification);
        return $verfication_json;
    }
}