<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class PostRepository
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            <<<'SQL'
                SELECT id, title, description, body, image, views, published_at
                FROM posts
                WHERE id = :id
                LIMIT 1
            SQL
        );
        $stmt->execute(['id' => $id]);
        $post = $stmt->fetch();

        if ($post === false) {
            return null;
        }

        $post['categories'] = $this->getCategoriesForPost($id);

        return $post;
    }

    public function incrementViews(int $id): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE posts SET views = views + 1 WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
    }

    /** До 3 других статей с пересечением категорий. */
    public function findRelated(int $postId, int $limit = 3): array
    {
        $stmt = $this->pdo->prepare(
            <<<'SQL'
                SELECT DISTINCT p.id, p.title, p.description, p.image, p.views, p.published_at
                FROM posts p
                INNER JOIN category_post cp ON cp.post_id = p.id
                WHERE cp.category_id IN (
                    SELECT category_id FROM category_post WHERE post_id = :post_id
                )
                  AND p.id <> :post_id_exclude
                ORDER BY p.published_at DESC
                LIMIT :limit
            SQL
        );
        $stmt->bindValue('post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue('post_id_exclude', $postId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function getCategoriesForPost(int $postId): array
    {
        $stmt = $this->pdo->prepare(
            <<<'SQL'
                SELECT c.id, c.name, c.description
                FROM categories c
                INNER JOIN category_post cp ON cp.category_id = c.id
                WHERE cp.post_id = :post_id
                ORDER BY c.name ASC
            SQL
        );
        $stmt->execute(['post_id' => $postId]);

        return $stmt->fetchAll();
    }
}
