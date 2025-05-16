<?php
namespace OAuth\Repositories;

use Config\Database;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function getClientEntity($clientIdentifier)
    {
        $stmt = $this->conn->prepare('SELECT * FROM oauth_clients WHERE client_id = ?');
        $stmt->bind_param('s', $clientIdentifier);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        $client = $result->fetch_assoc();
        
        $clientEntity = new \OAuth\Entities\ClientEntity();
        $clientEntity->setIdentifier($client['client_id']);
        $clientEntity->setName($client['client_id']);
        
        return $clientEntity;
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        $stmt = $this->conn->prepare('SELECT * FROM oauth_clients WHERE client_id = ?');
        $stmt->bind_param('s', $clientIdentifier);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        $client = $result->fetch_assoc();
        
        if (
            $client['client_secret'] === $clientSecret &&
            (
                $client['grant_types'] === null ||
                in_array($grantType, explode(',', $client['grant_types']))
            )
        ) {
            return true;
        }
        
        return false;
    }
}
?>