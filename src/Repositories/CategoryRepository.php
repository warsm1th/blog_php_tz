<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class CategoryRepository
{
    private const SORT_COLUMNS = [
        'date' => 'p.published_at',
        'views' => 'p.views',
    ];

    public function __construct(
        private PDO $pdo
    ) {
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, name, description FROM categories WHERE id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function findAllWithRecentPosts(int $perCategory = 3): array
    {
        $categories = $this->findAllWithPosts();
        if ($categories === []) {
            return [];
        }

        $perCategory = max(1, $perCategory);

        $stmt = $this->pdo->prepare(
            <<<'SQL'
                SELECT id, title, description, image, views, published_at, category_id
                FROM (
                    SELECT
                        p.id,
                        p.title,
                        p.description,
                        p.image,
                        p.views,
                        p.published_at,
                        cp.category_id,
                        ROW_NUMBER() OVER (
                            PARTITION BY cp.category_id
                            ORDER BY p.published_at DESC
                        ) AS rn
                    FROM posts p
                    INNER JOIN category_post cp ON cp.post_id = p.id
                ) ranked
                WHERE rn <= :limit
                ORDER BY category_id ASC, published_at DESC
            SQL
        );
        $stmt->bindValue('limit', $perCategory, PDO::PARAM_INT);
        $stmt->execute();

        $postsByCategory = [];
        foreach ($stmt->fetchAll() as $row) {
            $categoryId = (int) ($row['category_id'] ?? 0);
            unset($row['category_id']);
            $postsByCategory[$categoryId][] = $row;
        }

        foreach ($categories as &$category) {
            $categoryId = (int) ($category['id'] ?? 0);
            $category['posts'] = $postsByCategory[$categoryId] ?? [];
        }
        unset($category);

        return $categories;
    }

    /** Категории, в которых есть хотя бы одна статья. */
    private function findAllWithPosts(): array
    {
        $sql = <<<'SQL'
            SELECT c.id, c.name, c.description
            FROM categories c
            WHERE EXISTS (
                SELECT 1 FROM category_post cp WHERE cp.category_id = c.id
            )
            ORDER BY c.name ASC
        SQL;

        return $this->pdo->query($sql)->fetchAll();
    }

    public function getPosts(int $categoryId, string $sort, int $limit, int $offset): array
    {
        $orderBy = self::SORT_COLUMNS[$sort] ?? self::SORT_COLUMNS['date'];

        $stmt = $this->pdo->prepare(
            <<<SQL
                SELECT p.id, p.title, p.description, p.image, p.views, p.published_at
                FROM posts p
                INNER JOIN category_post cp ON cp.post_id = p.id
                WHERE cp.category_id = :category_id
                ORDER BY {$orderBy} DESC
                LIMIT :limit OFFSET :offset
            SQL
        );
        $stmt->bindValue('category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countPosts(int $categoryId): int
    {
        $stmt = $this->pdo->prepare(
            <<<'SQL'
                SELECT COUNT(*)
                FROM category_post
                WHERE category_id = :category_id
            SQL
        );
        $stmt->execute(['category_id' => $categoryId]);

        return (int) $stmt->fetchColumn();
    }
}
