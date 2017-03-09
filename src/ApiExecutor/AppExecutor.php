<?php

namespace Shucream0117\TwitCastingOAuth\ApiExecutor;

use GuzzleHttp\Client;
use Shucream0117\TwitCastingOAuth\Utils\Config;
use Shucream0117\TwitCastingOAuth\Constants\StatusCode;
use Shucream0117\TwitCastingOAuth\Entities\AccessToken;
use Shucream0117\TwitCastingOAuth\GrantFlow\AuthCodeGrant;

class AppExecutor extends ApiExecutorBase
{
    /** @var string */
    protected $clientId;
    /** @var string */
    protected $clientSecret;

    /**
     * @param Config $config
     * @param Client|null $client
     */
    public function __construct(Config $config, ?Client $client = null)
    {
        parent::__construct($client);
        $this->clientId = $config->get('client_id');
        $this->clientSecret = $config->get('client_secret');
    }

    /**
     * this api requires different request headers and content-type than others
     * @param string $code
     * @param AuthCodeGrant $codeGrant
     * @return AccessToken
     * @throws \Exception
     */
    public function requestAccessToken(string $code, AuthCodeGrant $codeGrant): AccessToken
    {
        $response = $this->client->post($this->getFullUrl('oauth2/access_token'), [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
            ],
            'form_params' => [
                'code' => $code,
                'grant_type' => 'authorization_code',
                'client_id' => $codeGrant->getClientId(),
                'client_secret' => $codeGrant->getClientSecret(),
                'redirect_uri' => $codeGrant->getCallbackUrl(),
            ],
        ]);
        if ($response->getStatusCode() !== StatusCode::OK) {
            $this->throwExceptionByStatusCode($response);
            exit;
        }
        if (!$body = $response->getBody()->getContents()) {
            throw new \Exception("response body is empty");
        }
        if (!$json = json_decode($body, true)) {
            throw new \Exception("failed to parse response body");
        }
        if (empty($json['access_token']) || empty($json['expires_in'])) {
            throw new \Exception("unexpected response format. response:{$body}");
        }
        return new AccessToken($json['access_token'], $json['expires_in']);
    }

    /**
     * @return array
     */
    protected function getRequestHeaderParams(): array
    {
        $token = base64_encode("{$this->clientId}:{$this->clientSecret}");
        return array_merge($this->getCommonHeaderParams(), [
            'Authorization' => "Basic {$token}",
        ]);
    }
}
