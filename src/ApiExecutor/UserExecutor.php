<?php

namespace Shucream0117\TwitCastingOAuth\ApiExecutor;

use GuzzleHttp\Client;
use Shucream0117\TwitCastingOAuth\Entities\AccessToken;

class UserExecutor extends ApiExecutorBase
{
    /** @var AccessToken */
    protected $accessToken;

    /**
     * @param AccessToken $accessToken
     * @param Client|null $client
     */
    public function __construct(AccessToken $accessToken, ?Client $client = null)
    {
        parent::__construct($client);
        $this->accessToken = $accessToken;
    }

    /**
     * @return array
     */
    protected function getRequestHeaderParams(): array
    {
        return array_merge($this->getCommonHeaderParams(), [
            'Authorization' => "Bearer {$this->accessToken->getAccessToken()}",
        ]);
    }

    /**
     * @return AccessToken
     */
    public function getAccessToken(): AccessToken
    {
        return $this->accessToken;
    }
}
