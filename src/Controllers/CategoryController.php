<?php

declare(strict_types=1);

namespace App\Controllers;

final class CategoryController
{
    public function show(array $params = []): void
    {
        $id = $params['id'] ?? '';
        echo 'category #' . $id;
    }
}
