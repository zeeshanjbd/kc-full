<?php

namespace App\Repositories;

class CourseRepository extends BaseRepository
{
    protected string $table = 'courses';

    /**
     * @return array
     */
    public function fetchAll(): array
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, cat.name AS main_category_name
            FROM courses c
            LEFT JOIN categories cat ON c.main_category_id = cat.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * filter by category_id.
     *
     * @param array $categoryIds
     * @return array
     */
    public function fetchAllByCategoryId(array $categoryIds): array
    {
        if (empty($categoryIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));

        $stmt = $this->pdo->prepare("
            SELECT c.*, cat.name AS main_category_name
            FROM courses c
            LEFT JOIN categories cat ON c.main_category_id = cat.id
            WHERE c.category_id IN ($placeholders)
        ");

        $stmt->execute($categoryIds);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param string $id
     * @return array
     */
    public function fetchById(string $id): array
    {
        $stmt = $this->pdo->prepare("
            SELECT c.*, cat.name AS main_category_name
            FROM courses c
            LEFT JOIN categories cat ON c.main_category_id = cat.id
            WHERE c.id = :id
        ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }
}
