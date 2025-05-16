<?php

header('Content-Type: application/json; charset=utf-8');

// Loader para Slim PSR-7
require_once __DIR__ . '/../vendor/autoload.php';

// Obtener y normalizar ruta
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Quitar el prefijo '/api' para trabajar con rutas limpias
$basePath = '/api';
$normalizedPath = str_replace($basePath, '', $path);

// Enrutamiento
switch (true) {
    case preg_match('#^/oauth/token#', $normalizedPath):
        require_once __DIR__ . '/../src/Oauth/Routes/AuthRoutes.php';
        // /../src/candidatos/index.php
        break;
        
    case preg_match('#^/candidatos#', $normalizedPath):
        // Verificar token de OAuth 2.0 antes de acceder a rutas protegidas
        require_once __DIR__ . '/../src/Oauth/Middleware/AuthMiddleware.php';
        
        // Solo continuamos si el token es válido
        if (\OAuth\Middleware\AuthMiddleware::verifyToken()) {
            require_once __DIR__ . '/../src/candidatos/index.php';
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada"]);
        break;
}