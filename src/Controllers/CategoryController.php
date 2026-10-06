<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\CategoryRepository;
use Smarty\Smarty;

final class CategoryController extends BaseController
{
    private const ALLOWED_SORT = ['date', 'views'];

    public function __construct(
        Smarty $smarty,
        private CategoryRepository $categories,
        private int $postsPerPage
    ) {
        parent::__construct($smarty);
    }

    public function show(array $params = []): void
    {
        $id = (int) ($params['id'] ?? 0);
        $category = $this->categories->findById($id);

        if ($category === null) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        $sort = (string) ($_GET['sort'] ?? 'date');
        if (!in_array($sort, self::ALLOWED_SORT, true)) {
            $sort = 'date';
        }

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = max(1, $this->postsPerPage);
        $total = $this->categories->countPosts($id);
        $totalPages = (int) ceil($total / $perPage);

        if ($totalPages > 0 && $page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $perPage;
        $posts = $this->categories->getPosts($id, $sort, $perPage, $offset);

        $this->render('category.tpl', [
            'pageTitle' => $category['name'] ?? 'Категория',
            'category' => $category,
            'posts' => $posts,
            'sort' => $sort,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
