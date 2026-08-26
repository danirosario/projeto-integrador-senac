<?php
// aprendendo a conectar o php com o banco de dados

//CREDENCIAIS DO BANCO DE DADOS

$server = "localhost";
$user = "root";
$pass = "123456";
$db = "lojacriartdb";

//CONEXÃO 

$conn = mysqli_connect($server, $user, $pass, $db);

// VERIFICAR CONEXÃO

if ($conn->connect_error) {
    die("connect error:" . $conn->connect_error);
}

$lifetime = 60 * 60 * 24 * 7; 

// 1. Tell the server to keep session files alive for 7 days
ini_set('session.gc_maxlifetime', $lifetime);

// 2. Configure lifetime and security parameters for the cookie
session_set_cookie_params([
    'lifetime' => $lifetime,
    'path'     => '/',
    'domain'   => '', 
    'secure'   => isset($_SERVER['HTTPS']), // Automatically detects HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);

// 3. Start the session if not already started
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
// echo "Connection successfully established."; 

?>
