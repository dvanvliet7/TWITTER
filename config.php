<?php

ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 1800,
    'domain' => 'localhost',
    'path' => '/',
    'secure' => true,
    'httponly' => true
]);

session_start();

if (!isset($_SESSION['last_regeneration'])) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
} else {
    $Interval = 60 * 30;
    if (time() - $_SESSION['last_regeneration'] >= $Interval) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}

// Example of setting a cookie with the SameSite attribute
setcookie("my_cookie", "cookie_value", [
    "expires" => time() + 3600, // Cookie expiration time
    "path" => "/",              // Path for the cookie
    "domain" => "locahost", // Domain for the cookie
    "secure" => true,           // Send the cookie only over HTTPS
    "httponly" => true,         // Cookie accessible only through HTTP
    "samesite" => "Strict",        // SameSite attribute: Lax, Strict, None
]);