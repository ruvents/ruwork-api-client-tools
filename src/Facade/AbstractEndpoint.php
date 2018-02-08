<?php

namespace Ruwork\ApiClientTools\Facade;

abstract class AbstractEndpoint implements EndpointInterface
{
    protected $options = [];
    protected $class;
    private $facade;
    private $hydrate = true;

    public function __construct(FacadeInterface $facade)
    {
        $this->facade = $facade;
        $this->configure();
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

    public function setHydrate($hydrate)
    {
        $this->hydrate = $hydrate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        return $this->facade->execute($this->options, $this->hydrate ? $this->class : null);
    }

    /**
     * @return void
     */
    abstract protected function configure();
}
