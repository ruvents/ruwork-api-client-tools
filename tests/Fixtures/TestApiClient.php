<?php

namespace Ruwork\ApiClientTools\Fixtures;

use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use Ruwork\ApiClientTools\ApiClientInterface;
use Ruwork\ApiClientTools\ResponseDecoder\ResponseDecoderInterface;

class TestApiClient implements ApiClientInterface
{
    private $client;
    private $decoder;

    public function __construct(HttpClient $client, ResponseDecoderInterface $decoder)
    {
        $this->client = $client;
        $this->decoder = $decoder;
    }

    /**
     * {@inheritdoc}
     */
    public function request(RequestInterface $request)
    {
        return $this->decoder->decode($this->client->sendRequest($request));
    }
}
