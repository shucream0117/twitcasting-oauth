<?php

namespace Shucream0117\TwitCastingOAuth\ApiExecutor;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Shucream0117\TwitCastingOAuth\Constants\StatusCode;
use Shucream0117\TwitCastingOAuth\Constants\Url;
use Shucream0117\TwitCastingOAuth\Exceptions\BadRequestException;
use Shucream0117\TwitCastingOAuth\Exceptions\ExceptionBase;
use Shucream0117\TwitCastingOAuth\Exceptions\ForbiddenException;
use Shucream0117\TwitCastingOAuth\Exceptions\InternalServerErrorException;
use Shucream0117\TwitCastingOAuth\Exceptions\NotFoundException;
use Shucream0117\TwitCastingOAuth\Exceptions\ServiceUnAvailableException;

abstract class ApiExecutorBase
{
    /** @var Client */
    protected $client;

    /**
     * @param Client|null $client
     */
    public function __construct(?Client $client)
    {
        if (is_null($client)) {
            $client = new Client();
        }
        $this->client = $client;
    }

    abstract protected function getRequestHeaderParams(): array;

    protected function getCommonHeaderParams(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Api-Version' => "2.0",
        ];
    }

    public function post(string $apiPath, array $params = [], array $headers)
    {
        $this->client->post(
            $this->getFullUrl($apiPath)
        );
    }

    public function get(string $apiPath, array $params = [], array $headers)
    {

    }

    public function put(string $apiPath, array $params = [], array $headers)
    {

    }

    public function delete(string $apiPath, array $params = [], array $headers)
    {

    }

    /**
     * @param string $apiPath
     * @return string
     */
    protected function getFullUrl(string $apiPath)
    {
        return Url::API_V2_SSL_URL . "/{$apiPath}";
    }

    /**
     * @param int $statusCode
     * @param ResponseInterface $response
     * @throws ExceptionBase
     */
    protected function throwExceptionByStatusCode(int $statusCode, ResponseInterface $response)
    {
        if ($statusCode < StatusCode::BAD_REQUEST) {
            throw new \InvalidArgumentException("status code({$statusCode}) is not an error");
        }

        $errorResponseString = $response->getBody()->getContents();
        if ($statusCode === StatusCode::BAD_REQUEST) {
            throw new BadRequestException($errorResponseString);
        }
        if ($statusCode === StatusCode::FORBIDDEN) {
            throw new ForbiddenException($errorResponseString);
        }
        if ($statusCode === StatusCode::NOT_FOUND) {
            throw new NotFoundException($errorResponseString);
        }
        if ($statusCode === StatusCode::INTERNAL_SERVER_ERROR) {
            throw new InternalServerErrorException($errorResponseString);
        }
        if ($statusCode === StatusCode::SERVICE_UNAVAILABLE) {
            throw new ServiceUnAvailableException($errorResponseString);
        }
        throw new \InvalidArgumentException("failed to handle status code. code={$statusCode}");
    }
}
