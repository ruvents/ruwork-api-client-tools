<?php

namespace Ruwork\ApiClientTools\Hydrator;

abstract class AbstractResult implements \IteratorAggregate
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param int|string $offset
     *
     * @return bool
     */
    public function __isset($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * @param int|string $offset
     *
     * @return mixed
     */
    public function __get($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * @param int|string $offset
     * @param mixed      $value
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function __set($offset, $value)
    {
        throw new \LogicException('Result is immutable.');
    }

    /**
     * @param int|string $offset
     *
     * @throws \LogicException
     *
     * @return void
     */
    public function __unset($offset)
    {
        throw new \LogicException('Result is immutable.');
    }

    /**
     * @return array
     */
    public static function getMap()
    {
        return [];
    }

    /**
     * @param int|string $offset
     *
     * @return bool
     */
    public function exists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
}
