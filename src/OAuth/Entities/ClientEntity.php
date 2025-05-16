<?php
namespace OAuth\Entities;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class ClientEntity implements ClientEntityInterface
{
    use EntityTrait, ClientTrait;
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setRedirectUri($uri)
    {
        $this->redirectUri = $uri;
    }
    
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }
    
    public function isConfidential()
    {
        return true;
    }
}
?>