<?php
/**
 * KrispiKas - Konfigurasi Database
 * 
 * Mendukung konfigurasi database dari XAMPP/MySQL lokal maupun server Railway.
 */

$host = getenv('MYSQLHOST') ?: getenv('MYSQL_HOST') ?: getenv('DB_HOST') ?: 'localhost';
$port = getenv('MYSQLPORT') ?: getenv('MYSQL_PORT') ?: getenv('DB_PORT') ?: '3306';
$db_name = getenv('MYSQLDATABASE') ?: getenv('MYSQL_DATABASE') ?: getenv('DB_DATABASE') ?: 'rajo_ameh_db';
$username = getenv('MYSQLUSER') ?: getenv('MYSQL_USER') ?: getenv('DB_USERNAME') ?: 'root';
$password = getenv('MYSQLPASSWORD') !== false ? getenv('MYSQLPASSWORD') : (getenv('MYSQL_PASSWORD') !== false ? getenv('MYSQL_PASSWORD') : (getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : ''));

// Dukungan khusus untuk Railway DATABASE_URL atau MYSQL_URL
$db_url = getenv('DATABASE_URL') ?: getenv('MYSQL_URL') ?: getenv('MYSQL_PUBLIC_URL');
if ($db_url) {
    $parsedUrl = parse_url($db_url);
    if ($parsedUrl !== false) {
        $host = $parsedUrl['host'] ?? $host;
        $port = $parsedUrl['port'] ?? $port;
        $db_name = ltrim($parsedUrl['path'] ?? '', '/') ?: $db_name;
        $username = $parsedUrl['user'] ?? $username;
        $password = $parsedUrl['pass'] ?? $password;
    }
}

return [
    'host'     => $host,
    'port'     => $port,
    'db_name'  => $db_name,
    'username' => $username,
    'password' => $password,
    'charset'  => 'utf8mb4'
];
