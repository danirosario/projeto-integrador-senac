<?php
$server = "localhost";
$user = "root";
$pass = "123456";
$db = "lojacriartdb";

$conn = mysqli_connect($server, $user, $pass, $db);

if ($conn->connect_error) {
    die("connect error:" . $conn->connect_error);
}

$lifetime = 60 * 60 * 24 * 7; 

ini_set('session.gc_maxlifetime', $lifetime);

session_set_cookie_params([
    'lifetime' => $lifetime,
    'path'     => '/',
    'domain'   => '', 
    'secure'   => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);

if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
