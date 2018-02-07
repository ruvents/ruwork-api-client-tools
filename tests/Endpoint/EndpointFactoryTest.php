<?php

namespace Ruwork\ApiClientTools\Endpoint;

use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Ruwork\ApiClientTools\Fixtures\Endpoint\TestEndpoint;
use Ruwork\ApiClientTools\Fixtures\TestApiClient;
use Ruwork\ApiClientTools\ResponseDecoder\JsonResponseDecoder;

class EndpointFactoryTest extends TestCase
{
    /**
     * @var EndpointFactory
     */
    private $factory;

    protected function setUp()
    {
        $apiClient = new TestApiClient(new Client(), new JsonResponseDecoder());
        $this->factory = new EndpointFactory($apiClient);
    }

    public function testCreateTestEndpoint()
    {
        $enpoint = $this->factory->create(TestEndpoint::class);
        $this->assertInstanceOf(TestEndpoint::class, $enpoint);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Class "a" does not exist.
     */
    public function testNoClassException()
    {
        $this->factory->create('a');
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Endpoint class Ruwork\ApiClientTools\Endpoint\EndpointFactoryTest must extend Ruwork\ApiClientTools\Endpoint\AbstractEndpoint.
     */
    public function testInvalidClassException()
    {
        $this->factory->create(self::class);
    }
}
