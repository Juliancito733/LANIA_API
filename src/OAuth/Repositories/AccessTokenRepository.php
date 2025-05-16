<?php
namespace OAuth\Repositories;

require_once __DIR__ . '/../../config/database.php';

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use mysqli;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    private $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        
        $stmt = $this->conn->prepare(
            'INSERT INTO oauth_access_tokens (access_token, client_id, user_id, expires, scope) VALUES (?, ?, ?, ?, ?)'
        );
        
        $token = $accessTokenEntity->getIdentifier();
        $clientId = $accessTokenEntity->getClient()->getIdentifier();
        $userId = $accessTokenEntity->getUserIdentifier();
        $expires = date('Y-m-d H:i:s', $accessTokenEntity->getExpiryDateTime()->getTimestamp());
        $scopes = $this->scopesToString($accessTokenEntity->getScopes());
        
        $stmt->bind_param('sssss', $token, $clientId, $userId, $expires, $scopes);
        $stmt->execute();
    }

    public function revokeAccessToken($tokenId)
    {
        $stmt = $this->conn->prepare('DELETE FROM oauth_access_tokens WHERE access_token = ?');
        $stmt->bind_param('s', $tokenId);
        $stmt->execute();
    }

    public function isAccessTokenRevoked($tokenId)
    {
        
        $stmt = $this->conn->prepare('SELECT 1 FROM oauth_access_tokens WHERE access_token = ?');
        $stmt->bind_param('s', $tokenId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows === 0;
    }

    public function getNewToken($client, array $scopes, $userIdentifier = null)
    {
        $accessToken = new \OAuth\Entities\AccessTokenEntity();
        $accessToken->setClient($client);
        
        if ($userIdentifier !== null) {
            $accessToken->setUserIdentifier($userIdentifier);
        }
        
        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }
        
        return $accessToken;
    }
    
    private function scopesToString(array $scopes)
    {
        return implode(' ', array_map(function ($scope) {
            return $scope->getIdentifier();
        }, $scopes));
    }
}
?>