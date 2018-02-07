<?php

namespace Ruwork\ApiClientTools\RequestProcessor;

use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use Ruwork\ApiClientTools\ResponseDecoder\ResponseDecoderInterface;

final class RequestProcessor implements RequestProcessorInterface
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
    public function process(RequestInterface $request)
    {
        $response = $this->client->sendRequest($request);

        return $this->decoder->decode($response);
    }
}
