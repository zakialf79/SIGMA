<?php
/**
 * KrispiKas - Konfigurasi Database
 * 
 * Mendukung konfigurasi database dari XAMPP/MySQL lokal maupun server Railway.
 */

function getEnvVar($key, $default = '') {
    $val = getenv($key);
    if ($val !== false && $val !== '') return $val;
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];
    return $default;
}

$host = getEnvVar('MYSQLHOST', getEnvVar('MYSQL_HOST', getEnvVar('DB_HOST', 'localhost')));
$port = getEnvVar('MYSQLPORT', getEnvVar('MYSQL_PORT', getEnvVar('DB_PORT', '3306')));
$db_name = getEnvVar('MYSQLDATABASE', getEnvVar('MYSQL_DATABASE', getEnvVar('DB_DATABASE', 'rajo_ameh_db')));
$username = getEnvVar('MYSQLUSER', getEnvVar('MYSQL_USER', getEnvVar('DB_USERNAME', 'root')));
$password = getEnvVar('MYSQLPASSWORD', getEnvVar('MYSQL_PASSWORD', getEnvVar('DB_PASSWORD', '')));

// Dukungan khusus untuk Railway DATABASE_URL atau MYSQL_URL
$db_url = getEnvVar('DATABASE_URL', getEnvVar('MYSQL_URL', getEnvVar('MYSQL_PUBLIC_URL', '')));
if ($db_url !== '') {
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
