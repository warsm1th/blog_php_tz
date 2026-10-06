<?php

declare(strict_types=1);

use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Router;

$root = dirname(__DIR__);

require $root . '/vendor/autoload.php';

$homeController = new HomeController();
$categoryController = new CategoryController();
$postController = new PostController();

$router = new Router();
$router->get('/', [$homeController, 'index']);
$router->get('/category/{id}', [$categoryController, 'show']);
$router->get('/post/{id}', [$postController, 'show']);

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
