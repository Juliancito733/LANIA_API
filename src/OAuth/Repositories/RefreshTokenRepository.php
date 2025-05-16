<?php
namespace OAuth\Repositories;

require_once __DIR__ . '/../../config/database.php';

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        global $conn;
        
        $refreshToken = $refreshTokenEntity->getIdentifier();
        $accessToken = $refreshTokenEntity->getAccessToken()->getIdentifier();
        $expires = date('Y-m-d H:i:s', $refreshTokenEntity->getExpiryDateTime()->getTimestamp());
        $clientId = $refreshTokenEntity->getAccessToken()->getClient()->getIdentifier();
        $userId = $refreshTokenEntity->getAccessToken()->getUserIdentifier();
        
        $stmt = $conn->prepare(
            'INSERT INTO oauth_refresh_tokens (refresh_token, access_token, client_id, user_id, expires) VALUES (?, ?, ?, ?, ?)'
        );
        
        $stmt->bind_param('sssss', $refreshToken, $accessToken, $clientId, $userId, $expires);
        $stmt->execute();
    }

    public function revokeRefreshToken($tokenId)
    {
        global $conn;
        
        $stmt = $conn->prepare('DELETE FROM oauth_refresh_tokens WHERE refresh_token = ?');
        $stmt->bind_param('s', $tokenId);
        $stmt->execute();
    }

    public function isRefreshTokenRevoked($tokenId)
    {
        global $conn;
        
        $stmt = $conn->prepare('SELECT 1 FROM oauth_refresh_tokens WHERE refresh_token = ?');
        $stmt->bind_param('s', $tokenId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows === 0;
    }

    public function getNewRefreshToken()
    {
        return new \OAuth\Entities\RefreshTokenEntity();
    }
}
?>