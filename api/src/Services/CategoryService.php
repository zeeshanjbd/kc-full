<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

readonly class CategoryService
{
    public function __construct(private CategoryRepository $repo) {}

    public function getAll(): array
    {
        $cats = $this->repo->fetchAll();
        foreach ($cats as &$c) {
            $c['count_of_courses'] = $this->repo->countCoursesRecursive($c['id']);
        }
        return $cats;
    }

    public function getById(string $id): array
    {
        $c = $this->repo->fetchById($id);
        $c['count_of_courses'] = $this->repo->countCoursesRecursive($id);
        return $c;
    }
}
