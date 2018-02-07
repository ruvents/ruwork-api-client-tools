<?php

namespace Ruwork\ApiClientTools\Hydrator;

final class HydratingIterator extends \IteratorIterator
{
    private $hydrator;
    private $class;

    public function __construct(HydratorInterface $hydrator, \Traversable $data, $class)
    {
        $this->hydrator = $hydrator;
        $this->class = $class;
        parent::__construct($data);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->hydrator->hydrate(parent::current(), $this->class);
    }
}
