<?php

namespace Ruwork\ApiClientTools\Fixtures\Hydrator;

class IteratorAggregate implements \IteratorAggregate
{
    private $iterator;

    public function __construct(\Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->iterator;
    }
}
