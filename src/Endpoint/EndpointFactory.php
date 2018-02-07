<?php

namespace Ruwork\ApiClientTools\Endpoint;

use Ruwork\ApiClientTools\ApiClientInterface;
use Ruwork\ApiClientTools\Http\RequestBuilder;
use Ruwork\ApiClientTools\Hydrator\HydratorInterface;

final class EndpointFactory
{
    private $apiClient;
    private $requestBuilder;
    private $hydrator;

    public function __construct(
        ApiClientInterface $apiClient,
        RequestBuilder $requestBuilder = null,
        HydratorInterface $hydrator = null
    ) {
        $this->apiClient = $apiClient;
        $this->requestBuilder = $requestBuilder ?: new RequestBuilder();
        $this->hydrator = $hydrator;
    }

    /**
     * @param string $class
     *
     * @throws \UnexpectedValueException
     *
     * @return AbstractEndpoint
     */
    public function create($class)
    {
        if (!class_exists($class)) {
            throw new \UnexpectedValueException(sprintf('Class "%s" does not exist.', $class));
        }

        if (AbstractEndpoint::class !== $class && !is_subclass_of($class, AbstractEndpoint::class)) {
            throw new \UnexpectedValueException(sprintf('Endpoint class %s must extend %s.', $class, AbstractEndpoint::class));
        }

        return new $class($this->apiClient, $this->requestBuilder, $this->hydrator);
    }
}
