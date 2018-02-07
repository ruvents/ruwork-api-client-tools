<?php

namespace Ruwork\ApiClientTools\RequestProcessor;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Ruwork\ApiClientTools\ResponseDecoder\JsonResponseDecoder;

class RequestProcessorTest extends TestCase
{
    public function testProcess()
    {
        $httpClient = new Client();
        $httpClient->addResponse(new Response(200, [], 'null'));
        $processor = new RequestProcessor($httpClient, new JsonResponseDecoder());

        $this->assertNull($processor->process(new Request('GET', '/')));
    }
}
