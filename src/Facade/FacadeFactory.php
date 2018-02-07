<?php

namespace Ruwork\ApiClientTools\Facade;

use Ruwork\ApiClientTools\ApiClientInterface;
use Ruwork\ApiClientTools\Hydrator\HydratorInterface;
use Ruwork\ApiClientTools\RequestFactory\RequestFactory;
use Ruwork\ApiClientTools\RequestFactory\RequestFactoryInterface;

class FacadeFactory
{
    private $apiClient;
    private $requestFactory;
    private $hydrator;

    public function __construct(
        ApiClientInterface $apiClient,
        RequestFactoryInterface $requestFactory = null,
        HydratorInterface $hydrator = null
    ) {
        $this->apiClient = $apiClient;
        $this->requestFactory = $requestFactory ?: new RequestFactory();
        $this->hydrator = $hydrator;
    }

    /**
     * @param string $class
     *
     * @return AbstractFacade
     */
    public function create($class)
    {
        if (!class_exists($class)) {
            throw new \UnexpectedValueException(sprintf('Class "%s" does not exist.', $class));
        }

        if (!is_subclass_of($class, AbstractFacade::class)) {
            throw new \UnexpectedValueException(sprintf('Facade class %s must extend %s.', $class, AbstractFacade::class));
        }

        return new $class($this->apiClient, $this->requestFactory, $this->hydrator);
    }
}
