<?php

namespace Ruwork\ApiClientTools\Client;

use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use Ruwork\ApiClientTools\ResponseDecoder\ResponseDecoderInterface;

final class Client implements ClientInterface
{
    private $httpClient;
    private $responseDecoder;

    public function __construct(HttpClient $httpClient, ResponseDecoderInterface $responseDecoder)
    {
        $this->httpClient = $httpClient;
        $this->responseDecoder = $responseDecoder;
    }

    /**
     * {@inheritdoc}
     */
    public function request(RequestInterface $request)
    {
        $response = $this->httpClient->sendRequest($request);

        return $this->responseDecoder->decodeResponse($response);
    }
}
