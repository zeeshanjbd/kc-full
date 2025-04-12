<?php

namespace App\Services;

use App\Repositories\CourseRepository;
use App\Repositories\CategoryRepository;

readonly class CourseService
{
    public function __construct(
        private CourseRepository $courseRepo,
        private CategoryRepository $categoryRepo
    )
    {
    }

    /**
     * @param string $catId
     * @return array
     */
    public function getAllByCategoryId(string $catId): array
    {
        $ids = $this->categoryRepo->getAllDescendantCategoryIds($catId);

        return $this->courseRepo->fetchAllByCategoryId($ids);
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->courseRepo->fetchAll();
    }

    /**
     * @param string $id
     * @return array
     */
    public function getById(string $id): array
    {
        return $this->courseRepo->fetchById($id);
    }
}
