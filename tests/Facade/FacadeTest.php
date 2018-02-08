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

class FacadeTest extends TestCase
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

    public function testExecute()
    {
        $this->httpClient->addResponse(new Response(200, [], '[]'));

        $result = $this->facade->execute([
            'endpoint' => '/test',
        ]);

        $request = $this->httpClient->getLastRequest();

        $this->assertSame([], $result);
        $this->assertSame('/test', $request->getUri()->getPath());
    }

    public function testExecuteWithClass()
    {
        $this->httpClient->addResponse(new Response(200, [], '[]'));

        $result = $this->facade->execute([
            'endpoint' => '/test',
        ], TestPhpDocResult::class);

        $request = $this->httpClient->getLastRequest();

        $this->assertInstanceOf(TestPhpDocResult::class, $result);
        $this->assertSame('/test', $request->getUri()->getPath());
    }

    public function testCreateTestFacade()
    {
        $enpoint = $this->facade->createEndpoint(TestEndpoint::class);
        $this->assertInstanceOf(TestEndpoint::class, $enpoint);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Class "a" does not exist.
     */
    public function testNoClassException()
    {
        $this->facade->createEndpoint('a');
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Endpoint class Ruwork\ApiClientTools\Facade\FacadeTest must implement Ruwork\ApiClientTools\Facade\EndpointInterface.
     */
    public function testInvalidClassException()
    {
        $this->facade->createEndpoint(self::class);
    }
}
