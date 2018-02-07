<?php

namespace Ruwork\ApiClientTools\Http;

abstract class AbstractArrayBuilder implements ArrayBuilderInterface
{
    protected $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function hasValue($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($name, $default = null)
    {
        return $this->hasValue($name) ? $this->data[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($name, $value)
    {
        $this->data[$name] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }
}
