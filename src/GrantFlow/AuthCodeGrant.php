<?php

namespace Shucream0117\TwitCastingOAuth\GrantFlow;

use Shucream0117\TwitCastingOAuth\ApiExecutor\AppExecutor;
use Shucream0117\TwitCastingOAuth\Config;
use Shucream0117\TwitCastingOAuth\Constants\Url;
use Shucream0117\TwitCastingOAuth\Entities\AccessToken;

class AuthCodeGrant extends GrantBase implements AuthorizeFlowInterface
{
    /** @var string */
    protected $clientId;

    /** @var string */
    protected $clientSecret;

    /** @var string */
    protected $callbackUrl;

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->clientId = $config->get('client_id');
        $this->clientSecret = $config->get('client_secret');
        $this->callbackUrl = $config->get('callback_url');
    }

    /**
     * @param string $csrfToken
     * @return string
     */
    public function getConfirmPageUrl(string $csrfToken = ''): string
    {
        $queryParams = [
            'client_id' => $this->clientId,
            'response_type' => 'code',
        ];
        if (!empty($csrfToken)) {
            $queryParams['state'] = $csrfToken;
        }
        $queryString = http_build_query($queryParams);
        return Url::API_V2_SSL_URL . "/oauth2/authorize?{$queryString}";
    }

    /**
     * @param string $code
     * @param AppExecutor $executor
     * @return AccessToken
     */
    public function requestAccessToken(string $code, AppExecutor $executor): AccessToken
    {
        return $executor->requestAccessToken($code, $this);
    }
}
