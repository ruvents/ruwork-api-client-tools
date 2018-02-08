<?php

namespace Ruwork\ApiClientTools\Facade;

interface FacadeInterface
{
    /**
     * @param array       $options
     * @param null|string $class
     *
     * @return mixed
     */
    public function execute(array $options, $class = null);

    /**
     * @param string $class
     *
     * @return EndpointInterface
     */
    public function createEndpoint($class);
}
