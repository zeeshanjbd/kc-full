<?php
declare(strict_types=1);
global $categoryService, $courseService;

use App\Services\DBImporterService;

require __DIR__ . '/config/bootstrap.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts = explode('/', $path);

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

if ($parts[0] === 'categories') {
    if ($method === 'GET' && count($parts) === 1) {
        echo json_encode($categoryService->getAll());
    } elseif ($method === 'GET' && isset($parts[1])) {
        echo json_encode($categoryService->getById($parts[1]));
    } else {
        http_response_code(404);
    }
} elseif ($parts[0] === 'courses') {
    if ($method === 'GET' && isset($parts[1]) && !empty($parts[1])) {
        echo json_encode($courseService->getById($parts[1]));
    } elseif ($method === 'GET' && isset($_GET['category_id']) && !empty($_GET['category_id'])) {
        echo json_encode($courseService->getAllByCategoryId($_GET['category_id']));
    } elseif ($method === 'GET') {
        echo json_encode($courseService->getAll());
    } else {
        http_response_code(404);
    }
} elseif ($parts[0] === 'importer') {
    try {
        DBImporterService::import();
        echo "Data successfully imported.\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
} else {
    http_response_code(404);
}
