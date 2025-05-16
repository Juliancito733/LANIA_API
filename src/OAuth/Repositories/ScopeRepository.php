<?php
namespace OAuth\Repositories;

use Config\Database;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use OAuth\Entities\ScopeEntity;


class ScopeRepository implements ScopeRepositoryInterface
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function getScopeEntityByIdentifier($identifier)
    {
        $stmt = $this->conn->prepare('SELECT * FROM oauth_scopes WHERE scope = ?');
        $stmt->bind_param('s', $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null; 
        }

        $scope = $result->fetch_assoc();

        $scopeEntity = new ScopeEntity();
        $scopeEntity->setIdentifier($scope['scope']);

        return $scopeEntity;
    }

    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
    {
        if (count($scopes) === 0) {
            $stmt = $this->conn->prepare('SELECT * FROM oauth_scopes WHERE is_default = 1');
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $scope = new ScopeEntity();
                $scope->setIdentifier($row['scope']);
                $scopes[] = $scope;
            }
        }

        return $scopes;
    }
}
