# PHP Blog

Простой блог на чистом PHP (без фреймворков), Smarty и MySQL.

## Стек

- PHP 8.2 / 8.1+
- Smarty
- MySQL 8
- SCSS
- Docker Compose (nginx, php-fpm, mysql)

## Функционал

- Главная — категории со статьями, по 3 последних поста и ссылка «Все статьи»
- Категория — список статей, сортировка по дате/просмотрам, пагинация
- Статья — полный текст, счётчик просмотров, блок похожих (общие категории)
- CLI: миграция схемы и сид тестовых данных

## Быстрый старт (Docker)

Нужны Docker и Docker Compose. `vendor/` должен быть на месте (`composer install` на хосте или в контейнере).

```bash
cp .env.example .env
composer install
docker compose up -d --build
docker compose exec php php database/migrate.php
docker compose exec php php database/seed.php
```

Сайт: http://localhost:8080

MySQL с хоста: `127.0.0.1:3307` (логин/пароль как в `.env.example`).  
Внутри контейнера php переменная `DB_HOST=mysql` задаётся в `docker-compose.yml`.

Остановка:

```bash
docker compose down
```

## Локальный запуск без Docker

1. Подними MySQL, создай пользователя/БД при необходимости (или используй root в `.env`).
2. Настрой окружение:

```bash
cp .env.example .env
composer install
php database/migrate.php
php database/seed.php
```

3. Запуск встроенного сервера:

```bash
php -S localhost:8000 -t public public/router.php
```

Открой http://localhost:8000

## Сборка стилей

Скомпилированный CSS уже в репозитории (`public/assets/css/style.css`). Пересборка из SCSS:

```bash
npm install
npm run build:css
```

## Структура

```text
public/                 # document root, front controller
resources/templates/    # Smarty
resources/scss/         # исходники стилей
src/                    # Router, Controllers, Repositories
config/                 # конфиг и загрузка .env
database/               # schema.sql, migrate.php, seed.php
docker/                 # nginx и entrypoint PHP
```

## Замечания

- Шаблоны экранируют вывод через `|escape`.
- Пустые категории на главную не попадают.
- Несуществующие категория/статья отдают 404.
