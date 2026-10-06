<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\PostRepository;
use Smarty\Smarty;

final class PostController extends BaseController
{
    public function __construct(
        Smarty $smarty,
        private PostRepository $posts
    ) {
        parent::__construct($smarty);
    }

    public function show(array $params = []): void
    {
        $id = (int) ($params['id'] ?? 0);
        $post = $this->posts->findById($id);

        if ($post === null) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        $this->posts->incrementViews($id);
        $post['views'] = (int) ($post['views'] ?? 0) + 1;

        $related = $this->posts->findRelated($id, 3);

        $this->render('post.tpl', [
            'pageTitle' => $post['title'] ?? 'Статья',
            'post' => $post,
            'related' => $related,
        ]);
    }
}
