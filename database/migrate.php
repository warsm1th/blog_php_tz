<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$config = require $root . '/config/app.php';
$db = $config['db'];

$serverDsn = sprintf(
    'mysql:host=%s;port=%d;charset=utf8mb4',
    $db['host'],
    $db['port']
);

$pdo = new PDO($serverDsn, $db['user'], $db['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$name = str_replace('`', '``', $db['name']);

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
