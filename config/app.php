<?php

declare(strict_types=1);

$root = dirname(__DIR__);

$envFile = $root . '/.env';
if (is_readable($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines !== false) {
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if ($name !== '' && getenv($name) === false) {
                putenv($name . '=' . $value);
                $_ENV[$name] = $value;
            }
        }
    }
}

return [
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => (int) (getenv('DB_PORT') ?: 3306),
        'name' => getenv('DB_NAME') ?: 'blog',
        'user' => getenv('DB_USER') ?: 'blog',
        'password' => getenv('DB_PASSWORD') !== false ? (string) getenv('DB_PASSWORD') : 'secret',
    ],
    'posts_per_page' => max(1, (int) (getenv('POSTS_PER_PAGE') ?: 5)),
    'paths' => [
        'root' => $root,
        'templates' => $root . '/resources/templates',
        'smarty_compile' => $root . '/var/smarty/compile',
    ],
];
