<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$config = require $root . '/config/app.php';
$db = $config['db'] ?? [];

$host = $db['host'] ?? '127.0.0.1';
$port = (int) ($db['port'] ?? 3306);
$name = str_replace('`', '``', (string) ($db['name'] ?? 'blog'));
$user = (string) ($db['user'] ?? 'blog');
$password = (string) ($db['password'] ?? '');

$serverDsn = sprintf(
    'mysql:host=%s;port=%d;charset=utf8mb4',
    $host,
    $port
);

$pdo = new PDO($serverDsn, $user, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
} catch (PDOException $e) {
    // пользователь без права CREATE DATABASE — ок, если база уже есть
}

$pdo->exec("USE `{$name}`");

$sql = file_get_contents($root . '/database/schema.sql');
if ($sql === false) {
    fwrite(STDERR, "Cannot read schema.sql\n");
    exit(1);
}

$pdo->exec($sql);

echo "Schema applied.\n";
