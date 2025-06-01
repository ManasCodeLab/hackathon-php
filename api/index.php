<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use App\Controllers\AuthController;
use App\Controllers\RegistrationController;
use App\Controllers\HackathonController;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Remove empty segments
$uri = array_filter($uri);

// Get the endpoint
$endpoint = $uri[2] ?? '';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Route the request
switch ($endpoint) {
    case 'auth':
        $controller = new AuthController($db);
        $controller->handleRequest();
        break;
    case 'registration':
        $controller = new RegistrationController($db);
        $controller->handleRequest();
        break;
    case 'hackathon':
        $controller = new HackathonController($db);
        $controller->handleRequest();
        break;
    default:
        http_response_code(404);
        echo json_encode(['message' => 'Endpoint not found']);
        break;
} 