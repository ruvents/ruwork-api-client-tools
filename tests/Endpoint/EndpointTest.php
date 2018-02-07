<?php

namespace Ruwork\ApiClientTools\Endpoint;

use GuzzleHttp\Psr7\Response;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Ruwork\ApiClientTools\ApiClientInterface;
use Ruwork\ApiClientTools\Fixtures\Endpoint\TestEndpoint;
use Ruwork\ApiClientTools\Fixtures\Hydrator\TestResult;
use Ruwork\ApiClientTools\Fixtures\TestApiClient;
use Ruwork\ApiClientTools\Hydrator\ResultHydrator;
use Ruwork\ApiClientTools\ResponseDecoder\JsonResponseDecoder;

class EndpointTest extends TestCase
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * @var ApiClientInterface
     */
    private $apiClient;

    /**
     * @var TestEndpoint
     */
    private $endpoint;

    protected function setUp()
    {
        $this->httpClient = new Client();
        $this->apiClient = new TestApiClient($this->httpClient, new JsonResponseDecoder());
        $factory = new EndpointFactory($this->apiClient, null, new ResultHydrator());
        $this->endpoint = $factory->create(TestEndpoint::class);
    }

    public function testGettersAndSetters()
    {
        $this->httpClient->addResponse(new Response(200, [], 'null'));

        $this->endpoint
            ->setData([
                'qa' => 1,
            ])
            ->setValue('qb', 2)
            ->getRawResult();

        $request = $this->httpClient->getLastRequest();

        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/test?qa=1&qb=2', (string) $request->getUri());
    }

    public function testQueryMagicSetter()
    {
        $this->httpClient->addResponse(new Response(200, [], 'null'));

        $this->endpoint
            ->a(1)
            ->B(1)
            ->getRawResult();

        $request = $this->httpClient->getLastRequest();

        $this->assertSame('/test?a=1&B=1', (string) $request->getUri());
    }

    public function testFormMagicSetter()
    {
        $this->httpClient->addResponse(new Response(200, [], 'null'));

        $this->endpoint
            ->getRequestBuilder()
            ->setMethod('PUT');

        $this->endpoint
            ->a(1)
            ->B(1)
            ->getRawResult();

        $request = $this->httpClient->getLastRequest();

        $this->assertSame('a=1&B=1', (string) $request->getBody());
    }

    public function testGetRawResult()
    {
        $this->httpClient->addResponse(new Response(200, [], json_encode(['a' => 1])));

        $result = $this->endpoint->getRawResult();

        $this->assertSame(['a' => 1], $result);
    }

    public function testGetResult()
    {
        $this->httpClient->addResponse(new Response(200, [], '[]'));

        $result = $this->endpoint
            ->setClass(TestResult::class)
            ->getResult();

        $this->assertInstanceOf(TestResult::class, $result);
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Method Ruwork\ApiClientTools\Fixtures\Endpoint\TestEndpoint::MagicCall() requires one argument.
     */
    public function testMagicCallNoArgumentsException()
    {
        $this->endpoint->MagicCall();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Class was not set. Use setClass().
     */
    public function testNoHydratorException()
    {
        $this->httpClient->addResponse(new Response(200, [], 'null'));

        $this->endpoint
            ->setEndpoint('/test')
            ->getResult();
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage This endpoint does not support hydration.
     */
    public function testNoClassException()
    {
        $factory = new EndpointFactory($this->apiClient);
        $endpoint = $factory->create(TestEndpoint::class);

        $this->httpClient->addResponse(new Response(200, [], 'null'));

        $endpoint->getResult();
    }
}
