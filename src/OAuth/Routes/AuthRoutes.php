<?php
require_once __DIR__ . '/../Controllers/AuthController.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER["REQUEST_METHOD"];

$routes  = [
    'POST' => [
        '/api/oauth/token' => [AuthController::class, 'token']
    ]
];

if (isset($routes[$method][$path])) {
    call_user_func($routes[$method][$path]);
} else {
    header("HTTP/1.1 404 Not Found");
    echo json_encode(['error' => 'Ruta no encontrada']);
    exit;
}
?>