<?php
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use OAuth\Repositories\AccessTokenRepository;
use OAuth\Repositories\ClientRepository;
use OAuth\Repositories\ScopeRepository;
use OAuth\Repositories\RefreshTokenRepository;
use Config\Database;

class AuthController {
    public static function token() {
        header('Content-Type: application/json; charset=UTF-8');
        
        $config = require_once __DIR__ . '/../../config/oauth.php';
        $conn = Database::connect();
        // Configurar server
        $clientRepository = new ClientRepository();
        $scopeRepository = new ScopeRepository();
        $accessTokenRepository = new AccessTokenRepository($conn);
        
        // Crear authorization server
        $server = new AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $config['private_key_path'],
            $config['encryption_key']
        );
        
        // Habilitar Client Credentials Grant
        $server->enableGrantType(
            new ClientCredentialsGrant(),
            new \DateInterval('PT' . $config['access_token_ttl'] . 'S')
        );
        
        // Procesar petición
        try {
            $response = $server->respondToAccessTokenRequest(
                \Slim\Psr7\Factory\ServerRequestFactory::createFromGlobals(),
                new \Slim\Psr7\Response()
            );
            
            // Enviar respuesta
            echo $response->getBody();
            
        } catch (OAuthServerException $e) {
            // Errores de OAuth
            http_response_code($e->getHttpStatusCode());
            echo json_encode([
                'error' => $e->getErrorType(),
                'message' => $e->getMessage()
            ]);
            
        } catch (\Exception $e) {
            // Errores del servidor
            http_response_code(500);
            echo json_encode([
                'error' => 'server_error',
                'message' => $e->getMessage(), // MOSTRAR EL MENSAJE REAL
                'trace' => $e->getTraceAsString() // Opcional: útil para depurar
            ]);
        }
    }
}
?>