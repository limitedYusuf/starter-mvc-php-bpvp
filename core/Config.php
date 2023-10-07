<?php

// Made By : Yusuf Limited
if (
    (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') &&
    (!isset($_SERVER['HTTP_X_FORWARDED_PROTO']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] !== 'https')
) {
    $httpsUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $httpsUrl);
    exit();
}
$protocol = 'https://';
$host = $_SERVER['HTTP_HOST'];
$directory = dirname($_SERVER['SCRIPT_NAME']);
define('BASE_URL', $protocol . $host . $directory . '/');
define('ASSET_URL', $protocol . $host . $directory . '/public/');
date_default_timezone_set('Asia/Makassar');
session_start();

// DB
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'ujikom_bpvp');
define('DB_PORT', 3306);

// PDO connection
function getDatabaseConnection() {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=' . DB_PORT;
    $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
    
    try {
        $conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        $conn->exec("SET NAMES 'utf8mb4'");
        return $conn;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        die();
    }
}