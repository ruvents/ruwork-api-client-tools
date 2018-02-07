<?php

namespace Ruwork\ApiClientTools\Hydrator;

interface HydratorInterface
{
    /**
     * @param mixed  $data
     * @param string $class
     *
     * @return mixed
     */
    public function hydrate($data, $class);
}
