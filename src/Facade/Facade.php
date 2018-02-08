<?php

namespace Ruwork\ApiClientTools\Facade;

use Ruwork\ApiClientTools\Client\ClientInterface;
use Ruwork\ApiClientTools\Hydrator\HydratorInterface;
use Ruwork\ApiClientTools\Hydrator\NullHydrator;
use Ruwork\ApiClientTools\RequestFactory\RequestFactory;
use Ruwork\ApiClientTools\RequestFactory\RequestFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Facade implements FacadeInterface
{
    private $client;
    private $requestFactory;
    private $hydrator;
    private $optionsResolver;

    public function __construct(
        ClientInterface $client,
        RequestFactoryInterface $requestFactory = null,
        HydratorInterface $hydrator = null
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory ?: new RequestFactory();
        $this->hydrator = $hydrator ?: new NullHydrator();
        $this->optionsResolver = new OptionsResolver();
        $this->requestFactory->configureOptions($this->optionsResolver);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(array $options, $class = null)
    {
        $options = $this->optionsResolver->resolve($options);
        $request = $this->requestFactory->createRequest($options);
        $data = $this->client->request($request);

        if (null === $class) {
            return $data;
        }

        return $this->hydrator->hydrate($data, $class);
    }

    /**
     * {@inheritdoc}
     */
    public function createEndpoint($class)
    {
        if (!class_exists($class)) {
            throw new \UnexpectedValueException(sprintf('Class "%s" does not exist.', $class));
        }

        if (!is_subclass_of($class, EndpointInterface::class)) {
            throw new \UnexpectedValueException(sprintf('Endpoint class %s must implement %s.', $class, EndpointInterface::class));
        }

        return new $class($this);
    }
}
