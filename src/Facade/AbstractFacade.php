<?php

namespace Ruwork\ApiClientTools\Facade;

use Ruwork\ApiClientTools\ApiClientInterface;
use Ruwork\ApiClientTools\Hydrator\HydratorInterface;
use Ruwork\ApiClientTools\RequestFactory\RequestFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFacade
{
    protected $apiClient;
    protected $requestFactory;
    protected $hydrator;
    protected $optionsResolver;
    protected $options = [];
    protected $class;

    public function __construct(
        ApiClientInterface $apiClient,
        RequestFactoryInterface $requestFactory,
        HydratorInterface $hydrator = null
    ) {
        $this->apiClient = $apiClient;
        $this->requestFactory = $requestFactory;
        $this->hydrator = $hydrator;
        $this->optionsResolver = new OptionsResolver();
        $this->requestFactory->configureOptions($this->optionsResolver);
    }

    public function __call($name, array $args)
    {
        if (count($args) < 1) {
            throw new \BadMethodCallException(sprintf('Method %s::%s() requires one argument.', static::class, $name));
        }

        return $this->setValue($name, $args[0]);
    }

    public function setData(array $data)
    {
        $this->options['data'] = $data;

        return $this;
    }

    public function setValue($name, $value)
    {
        $this->options['data'][$name] = $value;

        return $this;
    }

    public function getRawResult()
    {
        return $this->apiClient->request($this->createRequest());
    }

    public function getResult()
    {
        if (null === $this->hydrator || null === $this->class) {
            throw new \RuntimeException('This facade does not support hydration.');
        }

        return $this->hydrator->hydrate($this->getRawResult(), $this->class);
    }

    protected function createRequest()
    {
        $options = $this->optionsResolver->resolve($this->options);

        return $this->requestFactory->createRequest($options);
    }

    /**
     * @return void
     */
    abstract protected function configure();
}
