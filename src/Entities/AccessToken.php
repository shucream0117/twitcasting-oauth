<?php

namespace Shucream0117\TwitCastingOAuth\Entities;

class AccessToken
{
    /** @var string */
    private $accessToken;

    /** @var int */
    private $expiresIn;

    /** @var string */
    private $tokenType = 'Bearer';

    /**
     * @param string $accessToken
     * @param int $expiresIn
     */
    public function __construct(string $accessToken, int $expiresIn = 0)
    {
        $this->accessToken = $accessToken;
        $this->expiresIn = (int)$expiresIn;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }
}
