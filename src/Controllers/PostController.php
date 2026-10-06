<?php

declare(strict_types=1);

namespace App\Controllers;

final class PostController extends BaseController
{
    public function show(array $params = []): void
    {
        $id = (int) ($params['id'] ?? 0);

        $this->render('post.tpl', [
            'pageTitle' => 'Статья',
            'post' => [
                'id' => $id,
                'title' => 'Статья #' . $id,
                'description' => 'Краткое описание.',
                'body' => 'Текст статьи появится после подключения репозитория.',
                'image' => '',
                'views' => 0,
                'published_at' => '',
                'categories' => [],
            ],
            'related' => [],
        ]);
    }
}
