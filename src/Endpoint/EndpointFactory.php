<?php

namespace Ruwork\ApiClientTools\Endpoint;

use Ruwork\ApiClientTools\ApiClientInterface;
use Ruwork\ApiClientTools\Http\RequestBuilder;
use Ruwork\ApiClientTools\Hydrator\HydratorInterface;

final class EndpointFactory
{
    private $requestBuilder;
    private $processor;
    private $hydrator;

    public function __construct(
        ApiClientInterface $processor,
        RequestBuilder $requestBuilder = null,
        HydratorInterface $hydrator = null
    ) {
        $this->requestBuilder = $requestBuilder ?: new RequestBuilder();
        $this->processor = $processor;
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

        return new $class($this->processor, $this->requestBuilder, $this->hydrator);
    }
}
