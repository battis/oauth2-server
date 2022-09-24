<?php

namespace Battis\OAuth2\Server\Repositories;

use Battis\CRUD;
use Battis\OAuth2\Server\Entities\AccessToken;
use Doctrine\DBAL;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    public function __construct(DBAL\Connection $connection)
    {
        CRUD\Manager::get($connection);
    }

    public function getNewToken(
        ClientEntityInterface $clientEntity,
        array $scopes,
        $userIdentifier = null
    ) {
        $accessToken = new AccessToken();
        $accessToken->setClient($clientEntity);
        $accessToken->setUserIdentifier($userIdentifier);
        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }
        return $accessToken;
    }

    public function persistNewAccessToken(
        AccessTokenEntityInterface $accessTokenEntity
    ) {
        AccessToken::create([
            "identifier" => $accessTokenEntity->getIdentifier(),
            "expiryDateTime" => $accessTokenEntity
                ->getExpiryDateTime()
                ->format("Y-m-d H:i:s"),
            "userIdentifier" => $accessTokenEntity->getUserIdentifier(),
            "scopes" => json_encode($accessTokenEntity->getScopes()),
            "clientIdentifier" => $accessTokenEntity
                ->getClient()
                ->getIdentifier(),
        ]);
    }

    public function revokeAccessToken($tokenId)
    {
        AccessToken::delete($tokenId);
    }

    public function isAccessTokenRevoked($tokenId)
    {
        return AccessToken::read($tokenId) === null;
    }
}
