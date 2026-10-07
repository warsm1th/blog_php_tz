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
        $this->render('home.tpl', [
            'pageTitle' => 'Главная',
            'categories' => $this->categories->findAllWithRecentPosts(3),
        ]);
    }
}
