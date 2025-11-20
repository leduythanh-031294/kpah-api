<?php
error_reporting(0);
header("Content-Type: text/plain; charset=UTF-8");

function client_ip() {
    $keys = [
        'HTTP_CF_CONNECTING_IP', 'HTTP_TRUE_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP',
        'HTTP_CLIENT_IP', 'REMOTE_ADDR'
    ];

    foreach ($keys as $k) {
        if (!empty($_SERVER[$k])) {
            $ip = $_SERVER[$k];
            if (strpos($ip, ',') !== false) {
                $ip = trim(explode(',', $ip)[0]);
            }
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }
    return "0.0.0.0";
}

echo client_ip();
