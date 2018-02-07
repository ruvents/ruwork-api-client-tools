<?php

namespace Ruwork\ApiClientTools\Endpoint;

use Ruwork\ApiClientTools\ApiClientInterface;
use Ruwork\ApiClientTools\Http\RequestBuilder;
use Ruwork\ApiClientTools\Hydrator\HydratorInterface;

abstract class AbstractEndpoint
{
    protected $requestBuilder;
    protected $processor;
    protected $hydrator;
    protected $class;

    public function __construct(
        ApiClientInterface $processor,
        RequestBuilder $requestBuilder = null,
        HydratorInterface $hydrator = null
    ) {
        $this->processor = $processor;
        $this->requestBuilder = $requestBuilder ?: new RequestBuilder();
        $this->hydrator = $hydrator;
        $this->configure();
    }

    /**
     * @param string $name
     * @param array  $args
     *
     * @throws \BadMethodCallException
     *
     * @return $this
     */
    public function __call($name, array $args)
    {
        if (count($args) < 1) {
            throw new \BadMethodCallException(sprintf('Method %s::%s() requires one argument.', static::class, $name));
        }

        $this->requestBuilder->getDataBuilder()->setValue($name, $args[0]);

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->requestBuilder->getDataBuilder()->setData($data);

        return $this;
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function setValue($name, $value)
    {
        $this->requestBuilder->getDataBuilder()->setValue($name, $value);

        return $this;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    public function getRawResult()
    {
        return $this->processor->request($this->requestBuilder->build());
    }

    public function getResult()
    {
        if (null === $this->hydrator) {
            throw new \RuntimeException('This endpoint does not support hydration.');
        }

        if (null === $this->class) {
            throw new \RuntimeException('Class was not set. Use setClass().');
        }

        return $this->hydrator->hydrate($this->getRawResult(), $this->class);
    }

    /**
     * @return void
     */
    abstract protected function configure();
}
