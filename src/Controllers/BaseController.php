<?php

declare(strict_types=1);

namespace App\Controllers;

use Smarty\Smarty;

abstract class BaseController
{
    public function __construct(
        protected Smarty $smarty
    ) {
    }

    protected function render(string $template, array $vars = []): void
    {
        foreach ($vars as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        $this->smarty->display($template);
    }
}
