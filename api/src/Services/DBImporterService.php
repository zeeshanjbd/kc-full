<?php

namespace App\Services;

use Exception;
use PDO;

class DBImporterService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DBConnectionService::importConnection();
    }

    /**
     * @return void
     * @throws Exception
     */
    public static function import(): void
    {
        (new DBImporterService)->createDatabase();
    }

    /**
     * Ensure that the necessary database schema exists.
     * This method imports the schema if it does not exist.
     * @return void
     * @throws Exception
     */
    private function createDatabase(): void
    {
        $schemaDirectory = __DIR__ . '/../../database/migrations/';
        $schemaFiles = glob($schemaDirectory . '*.sql');

        if (empty($schemaFiles)) {
            throw new Exception("No SQL schema files found in: {$schemaDirectory}");
        }

        foreach ($schemaFiles as $schemaFilePath) {
            $sql = file_get_contents($schemaFilePath);
            $this->db->exec($sql);
        }

        $this->categorySeeder();
        $this->coursesSeeder();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function categorySeeder(): void
    {
        $categoryStatement = $this->db->prepare(
            "INSERT INTO categories (id, name, parent_id) 
            VALUES (:id, :name, :parent_id)"
        );

        $categoryJson = __DIR__ . '/../../database/data/categories.json';
        $data = json_decode(file_get_contents($categoryJson), true);
        if (!$data || !is_array($data)) {
            throw new \Exception("Invalid JSON file format.");
        }

        foreach ($data as $item) {
            try {
                $categoryStatement->execute([
                    'id' => $item['id'],
                    'name' => trim($item['name']),
                    'parent_id' => $item['parent'],
                ]);
            } catch (\Exception $e) {
                throw new \Exception("Failed to execute category query: {$e->getMessage()}");
            }
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    private function coursesSeeder(): void
    {
        $courseStatement = $this->db->prepare(
            "INSERT INTO courses (id, name, description, preview, category_id, main_category_id) 
            VALUES (:id, :name, :description, :preview, :category_id, :main_category_id)"
        );

        $coursesJson = __DIR__ . '/../../database/data/course_list.json';
        $data = json_decode(file_get_contents($coursesJson), true);
        if (!$data || !is_array($data)) {
            throw new \Exception("Invalid JSON file format.");
        }

        foreach ($data as $item) {
            try {

                $courseStatement->execute([
                    'id' => $item['course_id'],
                    'name' => trim($item['title']),
                    'description' => trim($item['description']),
                    'preview' => $item['image_preview'],
                    'category_id' => $item['category_id'],
                    'main_category_id' => $this->getMainCategoryID($item['category_id']),
                ]);
            } catch (\Exception $e) {
                throw new \Exception("Failed to execute course query: {$e->getMessage()}");
            }
        }
    }

    /**
     * @param string $category_id
     * @return string
     */
    private function getMainCategoryID(string $category_id): string
    {
        $levels = 0;
        while ($levels < 4) {
            $stmt = $this->db->prepare("
            SELECT parent_id 
            FROM categories 
            WHERE id = :id
            LIMIT 1
        ");
            $stmt->execute(['id' => $category_id]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$result || $result['parent_id'] === null) {
                return $category_id;
            }

            // Move up to the parent
            $category_id = $result['parent_id'];
            $levels++;
        }

        return $category_id;
    }
}
