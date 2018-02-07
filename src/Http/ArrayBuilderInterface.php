<?php

namespace Ruwork\ApiClientTools\Http;

interface ArrayBuilderInterface
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasValue($name);

    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getValue($name, $default = null);

    /**
     * @return array
     */
    public function getData();

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    public function setValue($name, $value);

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data);
}
