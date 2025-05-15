<?php

header('Content-Type: application/json; charset=utf-8');

// Obtener y normalizar ruta
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Quitar el prefijo '/api' para trabajar con rutas limpias
$basePath = '/api';
$normalizedPath = str_replace($basePath, '', $path);

// Enrutamiento
switch (true) {
    case preg_match('#^/candidatos#', $normalizedPath):
        require_once __DIR__ . '/../src/candidatos/index.php';
        break;

    default:
        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada"]);
        break;
}
?>
