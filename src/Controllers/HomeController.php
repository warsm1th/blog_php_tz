<?php

declare(strict_types=1);

namespace App\Controllers;

final class HomeController extends BaseController
{
    public function index(array $params = []): void
    {
        $this->render('home.tpl', [
            'pageTitle' => 'Главная',
            'categories' => [],
        ]);
    }
}
