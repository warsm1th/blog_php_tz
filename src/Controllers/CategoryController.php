<?php

declare(strict_types=1);

namespace App\Controllers;

final class CategoryController extends BaseController
{
    public function show(array $params = []): void
    {
        $id = (int) ($params['id'] ?? 0);

        $this->render('category.tpl', [
            'pageTitle' => 'Категория',
            'category' => [
                'id' => $id,
                'name' => 'Категория #' . $id,
                'description' => 'Описание появится после подключения репозитория.',
            ],
            'posts' => [],
            'sort' => 'date',
            'page' => 1,
            'totalPages' => 1,
        ]);
    }
}
