<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$pageTitle|default:'Блог'|escape}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600&family=Source+Serif+4:opsz,wght@8..60,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a class="site-logo" href="/">Блог</a>
        </div>
    </header>

    <main class="site-main">
        <div class="container">
            {block name="content"}{/block}
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>PHP Blog</p>
        </div>
    </footer>
</body>
</html>
