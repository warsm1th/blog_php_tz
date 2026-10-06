<?php

declare(strict_types=1);

$root = dirname(__DIR__);

require $root . '/vendor/autoload.php';

$config = require $root . '/config/app.php';
$db = $config['db'] ?? [];

$pdo = App\Database::connect($db);

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE category_post');
$pdo->exec('TRUNCATE TABLE posts');
$pdo->exec('TRUNCATE TABLE categories');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$categories = [
    [
        'name' => 'PHP',
        'description' => 'Язык, практика и типичные решения без лишнего фреймворк-шума.',
    ],
    [
        'name' => 'Базы данных',
        'description' => 'SQL, индексы, связи и запросы, которые не стыдно показать на ревью.',
    ],
    [
        'name' => 'Инфраструктура',
        'description' => 'Окружение, деплой и вещи вокруг приложения, без которых оно не живёт.',
    ],
];

$insertCategory = $pdo->prepare(
    'INSERT INTO categories (name, description) VALUES (:name, :description)'
);

$categoryIds = [];
foreach ($categories as $category) {
    $insertCategory->execute($category);
    $categoryIds[$category['name']] = (int) $pdo->lastInsertId();
}

$posts = [
    [
        'title' => 'PDO без магии: подготовленные запросы',
        'description' => 'Коротко о том, зачем bindValue и почему строковая конкатенация в SQL — плохая идея.',
        'body' => "Подготовленные выражения решают сразу две задачи: безопасность и предсказуемый план запроса.\n\nНа практике достаточно PDO::ATTR_ERRMODE => EXCEPTION и явного bind для LIMIT/OFFSET. Остальное — дисциплина: не собирать SQL из пользовательского ввода.",
        'image' => '/uploads/php-pdo.svg',
        'views' => 42,
        'published_at' => '2026-01-12 10:00:00',
        'categories' => ['PHP', 'Базы данных'],
    ],
    [
        'title' => 'Many-to-many без боли',
        'description' => 'Как связать статьи и категории через промежуточную таблицу и не утонуть в JOIN.',
        'body' => "Связь многие-ко-многим почти всегда выглядит одинаково: две сущности и pivot с составным ключом.\n\nГлавное — индексы на внешних ключах и понятные имена. Дальше SELECT с INNER JOIN читается проще, чем попытки хранить id категорий в JSON.",
        'image' => '/uploads/mysql-index.svg',
        'views' => 31,
        'published_at' => '2026-01-18 11:30:00',
        'categories' => ['Базы данных'],
    ],
    [
        'title' => 'Smarty: layout и наследование шаблонов',
        'description' => 'Зачем {extends} и {block}, если можно просто include.',
        'body' => "Наследование шаблонов держит общий каркас страницы в одном месте. Дочерний шаблон заполняет только content.\n\nДля блога этого достаточно: меньше копипасты, проще менять шапку и подвал.",
        'image' => '/uploads/smarty-tpl.svg',
        'views' => 27,
        'published_at' => '2026-02-02 09:15:00',
        'categories' => ['PHP'],
    ],
    [
        'title' => 'Front Controller на чистом PHP',
        'description' => 'Одна точка входа, простой роутер и почему это удобнее россыпи php-файлов.',
        'body' => "Front Controller собирает запрос в одном месте: автозагрузка, конфиг, роутинг, ответ.\n\nДля учебного проекта не нужен контейнер и middleware pipeline. Нужна прозрачная последовательность вызовов, которую можно объяснить за минуту.",
        'image' => '/uploads/routing.svg',
        'views' => 55,
        'published_at' => '2026-02-10 14:00:00',
        'categories' => ['PHP'],
    ],
    [
        'title' => 'Пагинация: LIMIT, OFFSET и здравый смысл',
        'description' => 'Когда OFFSET нормален, а когда уже пора думать о keyset.',
        'body' => "Для админок и небольших списков OFFSET вполне уместен. Считаете total, делите на page size, отдаёте LIMIT.\n\nВажно не забыть whitelist для сортировки: пользователь выбирает режим, а в SQL попадает только заранее известная колонка.",
        'image' => '/uploads/pagination.svg',
        'views' => 19,
        'published_at' => '2026-02-20 16:45:00',
        'categories' => ['Базы данных', 'PHP'],
    ],
    [
        'title' => 'Индексы, которые реально помогают',
        'description' => 'published_at и views — не декоративные поля, если по ним сортируете.',
        'body' => "Если страница категории сортирует по дате или просмотрам, индексы на этих колонках окупаются сразу.\n\nНе нужно индексировать всё подряд. Нужно индексировать то, что встречается в WHERE и ORDER BY на горячих страницах.",
        'image' => '/uploads/mysql-index.svg',
        'views' => 67,
        'published_at' => '2026-03-01 12:00:00',
        'categories' => ['Базы данных'],
    ],
    [
        'title' => 'Docker Compose для локальной разработки',
        'description' => 'PHP-FPM, Nginx и MySQL в трёх сервисах — и одинаковое окружение у всей команды.',
        'body' => "Compose снимает классическое «у меня работает». Сервисы поднимаются одной командой, переменные окружения совпадают с .env приложения.\n\nДля тестового задания это ещё и сигнал, что вы думаете о проверяющем.",
        'image' => '/uploads/docker-compose.svg',
        'views' => 38,
        'published_at' => '2026-03-08 18:20:00',
        'categories' => ['Инфраструктура'],
    ],
    [
        'title' => 'Репозиторий вместо SQL в контроллере',
        'description' => 'Тонкий контроллер и место, где живут запросы.',
        'body' => "Контроллер должен собрать входные данные и отдать результат в шаблон. SQL лучше держать рядом с PDO в репозитории.\n\nТак проще читать код и менять запрос, не размазывая его по экшенам.",
        'image' => '/uploads/php-pdo.svg',
        'views' => 23,
        'published_at' => '2026-03-15 10:10:00',
        'categories' => ['PHP'],
    ],
    [
        'title' => 'Логи и права на var/ в проде',
        'description' => 'Мелочь, из-за которой Smarty внезапно не компилирует шаблоны.',
        'body' => "Compile-директория должна существовать и быть доступна на запись процессу PHP.\n\nВ Docker это обычно решается volume или созданием каталога на старте. Локально — mkdir в bootstrap, как в index.php.",
        'image' => '/uploads/docker-compose.svg',
        'views' => 14,
        'published_at' => '2026-03-22 08:40:00',
        'categories' => ['Инфраструктура', 'PHP'],
    ],
    [
        'title' => 'Похожие статьи через пересечение категорий',
        'description' => 'Простой SQL-приём для блока «вам может быть интересно».',
        'body' => "Идея простая: взять категории текущей статьи, найти другие посты с теми же category_id, исключить текущий id, ограничить LIMIT 3.\n\nЭто не ML и не идеальная рекомендация, но для блога работает честно и прозрачно.",
        'image' => '/uploads/routing.svg',
        'views' => 11,
        'published_at' => '2026-03-28 13:25:00',
        'categories' => ['Базы данных', 'PHP'],
    ],
];

$insertPost = $pdo->prepare(
    <<<'SQL'
        INSERT INTO posts (title, description, body, image, views, published_at)
        VALUES (:title, :description, :body, :image, :views, :published_at)
    SQL
);

$insertPivot = $pdo->prepare(
    'INSERT INTO category_post (category_id, post_id) VALUES (:category_id, :post_id)'
);

foreach ($posts as $post) {
    $categoryNames = $post['categories'];
    unset($post['categories']);

    $insertPost->execute($post);
    $postId = (int) $pdo->lastInsertId();

    foreach ($categoryNames as $categoryName) {
        $categoryId = $categoryIds[$categoryName] ?? null;
        if ($categoryId === null) {
            continue;
        }

        $insertPivot->execute([
            'category_id' => $categoryId,
            'post_id' => $postId,
        ]);
    }
}

echo 'Seed completed: ' . count($categories) . ' categories, ' . count($posts) . " posts.\n";
