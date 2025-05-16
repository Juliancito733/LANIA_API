<?php
namespace OAuth\Middleware;

use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use OAuth\Repositories\AccessTokenRepository;
use Slim\Psr7\Factory\ServerRequestFactory;
use Config\Database;

class AuthMiddleware {
    public static function verifyToken() {
        $config = require_once __DIR__ . '/../../config/oauth.php';

        $conn = Database::connect();
        
        $accessTokenRepository = new AccessTokenRepository($conn);
        
        $resourceServer = new ResourceServer(
            $accessTokenRepository,
            $config['public_key_path']
        );
        
        try {
            $request = \Slim\Psr7\Factory\ServerRequestFactory::createFromGlobals();
            $request = $resourceServer->validateAuthenticatedRequest($request);
            
            return true;
            
        } catch (OAuthServerException $e) {
            // Token no válido
            header('Content-Type: application/json; charset=UTF-8');
            http_response_code($e->getHttpStatusCode());
            echo json_encode([
                'error' => $e->getErrorType(),
                'message' => $e->getMessage()
            ]);
            exit;
            
        } catch (\Exception $e) {
            // Error del servidor
            header('Content-Type: application/json; charset=UTF-8');
            http_response_code(500);
            echo json_encode([
                'error' => 'server_error',
                'message' => 'Se produjo un error en el servidor'
            ]);
            exit;
        }
    }
}
?>