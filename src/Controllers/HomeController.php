<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\CategoryRepository;
use Smarty\Smarty;

final class HomeController extends BaseController
{
    public function __construct(
        Smarty $smarty,
        private CategoryRepository $categories
    ) {
        parent::__construct($smarty);
    }

    public function index(array $params = []): void
    {
        $categories = $this->categories->findAllWithPosts();

        foreach ($categories as &$category) {
            $categoryId = (int) ($category['id'] ?? 0);
            $category['posts'] = $this->categories->getRecentPosts($categoryId, 3);
        }
        unset($category);

        $this->render('home.tpl', [
            'pageTitle' => 'Главная',
            'categories' => $categories,
        ]);
    }
}
