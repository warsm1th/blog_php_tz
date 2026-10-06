<?php

declare(strict_types=1);

use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\PostController;
use App\Database;
use App\Repositories\CategoryRepository;
use App\Repositories\PostRepository;
use App\Router;
use Smarty\Smarty;

$root = dirname(__DIR__);

require $root . '/vendor/autoload.php';

$config = require $root . '/config/app.php';

$compileDir = $config['paths']['smarty_compile'] ?? ($root . '/var/smarty/compile');
if (!is_dir($compileDir) && !mkdir($compileDir, 0775, true) && !is_dir($compileDir)) {
    throw new RuntimeException('Cannot create Smarty compile directory: ' . $compileDir);
}

$smarty = new Smarty();
$smarty->setTemplateDir($config['paths']['templates'] ?? ($root . '/templates'));
$smarty->setCompileDir($compileDir);
// экранирование явно через |escape в шаблонах
$smarty->setEscapeHtml(false);

$pdo = Database::connect($config['db'] ?? []);
$categoryRepository = new CategoryRepository($pdo);
$postRepository = new PostRepository($pdo);

$postsPerPage = (int) ($config['posts_per_page'] ?? 5);

$homeController = new HomeController($smarty, $categoryRepository);
$categoryController = new CategoryController($smarty, $categoryRepository, $postsPerPage);
$postController = new PostController($smarty, $postRepository);

$router = new Router();
$router->get('/', [$homeController, 'index']);
$router->get('/category/{id}', [$categoryController, 'show']);
$router->get('/post/{id}', [$postController, 'show']);

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
