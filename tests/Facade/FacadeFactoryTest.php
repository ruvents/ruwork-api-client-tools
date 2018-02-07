<?php

namespace Ruwork\ApiClientTools\Facade;

use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Ruwork\ApiClientTools\Fixtures\Facade\TestFacade;
use Ruwork\ApiClientTools\Fixtures\TestApiClient;
use Ruwork\ApiClientTools\ResponseDecoder\JsonResponseDecoder;

class FacadeFactoryTest extends TestCase
{
    /**
     * @var FacadeFactory
     */
    private $factory;

    protected function setUp()
    {
        $apiClient = new TestApiClient(new Client(), new JsonResponseDecoder());
        $this->factory = new FacadeFactory($apiClient);
    }

    public function testCreateTestFacade()
    {
        $enpoint = $this->factory->create(TestFacade::class);
        $this->assertInstanceOf(TestFacade::class, $enpoint);
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
     * @expectedExceptionMessage Facade class Ruwork\ApiClientTools\Facade\FacadeFactoryTest must extend Ruwork\ApiClientTools\Facade\AbstractFacade.
     */
    public function testInvalidClassException()
    {
        $this->factory->create(self::class);
    }
}
