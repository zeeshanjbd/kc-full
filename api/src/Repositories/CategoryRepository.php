<?php

namespace App\Repositories;

use PDO;

class CategoryRepository extends BaseRepository
{
    protected string $table = 'categories';

    /**
     * Count courses in this category and all its descendants.
     *
     * @param string $id
     * @return int
     */
    public function countCoursesRecursive(string $id): int
    {
        // count direct
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM courses WHERE category_id = ?'
        );
        $stmt->execute([$id]);
        $count = (int)$stmt->fetchColumn();

        // recurse into children
        $stmt2 = $this->pdo->prepare(
            'SELECT id FROM categories WHERE parent_id = ?'
        );
        $stmt2->execute([$id]);

        foreach ($stmt2->fetchAll(PDO::FETCH_COLUMN) as $childId) {
            $count += $this->countCoursesRecursive($childId);
        }

        return $count;
    }


    /**
     * Get all category IDs that are descendants (up to 4 levels) of the current category ID.
     *
     * @param string $id
     * @return array
     */
    public function getAllDescendantCategoryIds(string $id): array
    {
        $stmt = $this->pdo->prepare("
            WITH RECURSIVE category_hierarchy AS (
                -- Select the current category
                SELECT id, 0 AS depth
                FROM categories
                WHERE id = :id
    
                UNION ALL
    
                -- Select child categories (up to 4 levels)
                SELECT c.id, ch.depth + 1
                FROM categories c
                INNER JOIN category_hierarchy ch ON c.parent_id = ch.id
                WHERE ch.depth < 4
            )
            SELECT id FROM category_hierarchy;
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
