<?php

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigins = [
    'http://0.0.0.0:8080',
    'http://localhost:8080',
    'http://127.0.0.1:8080',
    'http://0.0.0.0:5500',
    'http://localhost:5500',
    'http://127.0.0.1:5500',
];

in_array($origin, $allowedOrigins) ?
    header("Access-Control-Allow-Origin: $origin") : null;
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../config/config.php';

$uri = strtok($_SERVER['REQUEST_URI'], '?');
match ($uri) {
    '/api/users' => require __DIR__ . '/../src/api.php',
    default => notFound(),
};

function notFound(): void
{
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}