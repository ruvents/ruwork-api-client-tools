<?php

namespace Ruwork\ApiClientTools\Facade;

use GuzzleHttp\Psr7\Response;
use Http\Mock\Client as HttpClient;
use PHPUnit\Framework\TestCase;
use Ruwork\ApiClientTools\Client\Client;
use Ruwork\ApiClientTools\Fixtures\Facade\TestEndpoint;
use Ruwork\ApiClientTools\Fixtures\Hydrator\TestPhpDocResult;
use Ruwork\ApiClientTools\Hydrator\PhpDocResultHydrator;
use Ruwork\ApiClientTools\RequestFactory\RequestFactory;
use Ruwork\ApiClientTools\ResponseDecoder\JsonResponseDecoder;

class AbstractEndpointTest extends TestCase
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Facade
     */
    private $facade;

    protected function setUp()
    {
        $this->httpClient = new HttpClient();
        $client = new Client($this->httpClient, new JsonResponseDecoder());
        $this->facade = new Facade($client, new RequestFactory(), new PhpDocResultHydrator());
    }

    public function test()
    {
        $this->httpClient->addResponse(new Response(200, [], '[]'));

        /** @var TestEndpoint $endpoint */
        $endpoint = $this->facade->createEndpoint(TestEndpoint::class);

        $result = $endpoint
            ->setData([
                'a' => 1,
                'b' => 1,
            ])
            ->setValue('b', 1)
            ->c(3)
            ->execute();

        $request = $this->httpClient->getLastRequest();

        $this->assertInstanceOf(TestPhpDocResult::class, $result);
        $this->assertSame('/test?a=1&b=1&c=3', (string) $request->getUri());
    }

    public function testHydrateFalse()
    {
        $this->httpClient->addResponse(new Response(200, [], '[]'));

        /** @var TestEndpoint $endpoint */
        $endpoint = $this->facade->createEndpoint(TestEndpoint::class);

        $result = $endpoint
            ->setHydrate(false)
            ->execute();

        $this->assertSame([], $result);
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Method Ruwork\ApiClientTools\Fixtures\Facade\TestEndpoint::a() requires one argument.
     */
    public function testMagicCallNoArgs()
    {
        $this->facade
            ->createEndpoint(TestEndpoint::class)
            ->a();
    }
}
