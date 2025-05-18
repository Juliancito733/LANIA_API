<?php
namespace Utils;

class CorsHandler {
    /**
     * Configura los encabezados CORS para todas las respuestas
     * 
     * @param string $allowOrigin Origen permitido ('*' para todos)
     * @param array $allowMethods Métodos HTTP permitidos
     * @param array $allowHeaders Encabezados permitidos
     * @param int $maxAge Tiempo de caché para las respuestas preflight (en segundos)
     */
    public static function configureCors(
        $allowOrigin = '*',
        $allowMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        $allowHeaders = ['Content-Type', 'Authorization', 'X-Requested-With'],
        $maxAge = 86400
    ) {
        // Configurar encabezados
        header('Access-Control-Allow-Origin: ' . $allowOrigin);
        header('Access-Control-Allow-Methods: ' . implode(', ', $allowMethods));
        header('Access-Control-Allow-Headers: ' . implode(', ', $allowHeaders));
        header('Access-Control-Max-Age: ' . $maxAge);
        
        // Permitir enviar cookies en solicitudes CORS (si es necesario)
        // header('Access-Control-Allow-Credentials: true');
        
        // Manejar solicitudes preflight OPTIONS
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('Content-Type: application/json; charset=utf-8');
            exit(0);
        }
    }
}
?>