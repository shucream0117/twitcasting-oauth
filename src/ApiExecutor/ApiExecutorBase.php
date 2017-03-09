<?php

namespace Shucream0117\TwitCastingOAuth\ApiExecutor;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Shucream0117\TwitCastingOAuth\Constants\StatusCode;
use Shucream0117\TwitCastingOAuth\Constants\Url;
use Shucream0117\TwitCastingOAuth\Exceptions\BadRequestException;
use Shucream0117\TwitCastingOAuth\Exceptions\ForbiddenException;
use Shucream0117\TwitCastingOAuth\Exceptions\InternalServerErrorException;
use Shucream0117\TwitCastingOAuth\Exceptions\NotFoundException;
use Shucream0117\TwitCastingOAuth\Exceptions\ServiceUnAvailableException;

abstract class ApiExecutorBase
{
    /** @var Client */
    protected $client;

    const API_VERSION = '2.0';

    /**
     * @param Client|null $client
     */
    public function __construct(?Client $client = null)
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
            'X-Api-Version' => static::API_VERSION,
        ];
    }

    /**
     * send post request
     *
     * @param string $apiPath
     * @param array $params
     * @param array $additionalHeaders
     * @return ResponseInterface
     * @throws BadRequestException
     * @throws ForbiddenException
     * @throws InternalServerErrorException
     * @throws NotFoundException
     * @throws ServiceUnAvailableException
     */
    public function post(string $apiPath, array $params = [], array $additionalHeaders = []): ResponseInterface
    {
        $response = $this->client->post(
            $this->getFullUrl($apiPath), [
            'headers' => array_merge(
                $this->getCommonHeaderParams(), $this->getRequestHeaderParams(), $additionalHeaders
            ),
            'json' => $params,
        ]);
        $statusCode = $response->getStatusCode();
        if ($statusCode < StatusCode::BAD_REQUEST) {
            return $response;
        }
        $this->throwExceptionByStatusCode($response);
    }

    /**
     * send get request
     *
     * @param string $apiPath
     * @param array $params
     * @param array $additionalHeaders
     * @return ResponseInterface
     * @throws BadRequestException
     * @throws ForbiddenException
     * @throws InternalServerErrorException
     * @throws NotFoundException
     * @throws ServiceUnAvailableException
     */
    public function get(string $apiPath, array $params = [], array $additionalHeaders = []): ResponseInterface
    {
        $queryString = http_build_query($params);
        $url = "{$this->getFullUrl($apiPath)}?{$queryString}";
        $response = $this->client->get(
            $url, [
            'headers' => array_merge(
                $this->getCommonHeaderParams(), $this->getRequestHeaderParams(), $additionalHeaders
            ),
        ]);
        $statusCode = $response->getStatusCode();
        if ($statusCode < StatusCode::BAD_REQUEST) {
            return $response;
        }
        $this->throwExceptionByStatusCode($response);
    }

    /**
     * send put request
     *
     * @param string $apiPath
     * @param array $params
     * @param array $additionalHeaders
     * @return ResponseInterface
     * @throws BadRequestException
     * @throws ForbiddenException
     * @throws InternalServerErrorException
     * @throws NotFoundException
     * @throws ServiceUnAvailableException
     */
    public function put(string $apiPath, array $params = [], array $additionalHeaders = []): ResponseInterface
    {
        $response = $this->client->put(
            $this->getFullUrl($apiPath), [
            'headers' => array_merge(
                $this->getCommonHeaderParams(), $this->getRequestHeaderParams(), $additionalHeaders
            ),
            'json' => $params,
        ]);
        $statusCode = $response->getStatusCode();
        if ($statusCode < StatusCode::BAD_REQUEST) {
            return $response;
        }
        $this->throwExceptionByStatusCode($response);
    }

    /**
     * send delete request
     *
     * @param string $apiPath
     * @param array $params
     * @param array $additionalHeaders
     * @return ResponseInterface
     * @throws BadRequestException
     * @throws ForbiddenException
     * @throws InternalServerErrorException
     * @throws NotFoundException
     * @throws ServiceUnAvailableException
     */
    public function delete(string $apiPath, array $params = [], array $additionalHeaders = []): ResponseInterface
    {
        $queryString = http_build_query($params);
        $url = "{$this->getFullUrl($apiPath)}?{$queryString}";
        $response = $this->client->delete(
            $url, [
            'headers' => array_merge(
                $this->getCommonHeaderParams(), $this->getRequestHeaderParams(), $additionalHeaders
            ),
        ]);
        $statusCode = $response->getStatusCode();
        if ($statusCode < StatusCode::BAD_REQUEST) {
            return $response;
        }
        $this->throwExceptionByStatusCode($response);
    }

    /**
     * @param string $apiPath
     * @return string
     */
    protected function getFullUrl(string $apiPath): string
    {
        return Url::API_V2_SSL_URL . "/{$apiPath}";
    }

    /**
     * @param ResponseInterface $response
     * @return void
     *
     * @throws BadRequestException
     * @throws ForbiddenException
     * @throws InternalServerErrorException
     * @throws NotFoundException
     * @throws ServiceUnAvailableException
     */
    protected function throwExceptionByStatusCode(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();
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
