<?php

namespace Ruwork\ApiClientTools\Hydrator;

class NullHydrator implements HydratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function hydrate($data, $class)
    {
        throw new \RuntimeException('Hydrating is not supported.');
    }
}
