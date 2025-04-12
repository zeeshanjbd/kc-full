<?php

use App\Repositories\CategoryRepository;
use App\Repositories\CourseRepository;
use App\Services\CategoryService;
use App\Services\CourseService;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Helpers/env_loader.php';

$categoryRepo = new CategoryRepository();
$courseRepo   = new CourseRepository();

$categoryService = new CategoryService($categoryRepo);
$courseService   = new CourseService($courseRepo, $categoryRepo);
