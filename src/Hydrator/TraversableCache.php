<?php

namespace Ruwork\ApiClientTools\Hydrator;

final class TraversableCache implements \IteratorAggregate
{
    /**
     * @var \Iterator
     */
    private $iterator;
    private $cache = [];

    public function __construct(\Traversable $iterator)
    {
        if ($iterator instanceof \IteratorAggregate) {
            $iterator = $iterator->getIterator();
        }

        $this->iterator = $iterator;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        foreach ($this->cache as $key => $value) {
            yield $key => $value;
        }

        while ($this->iterator->valid()) {
            $key = $this->iterator->key();
            $value = $this->iterator->current();
            $this->cache[$key] = $value;

            yield $key => $value;

            $this->iterator->next();
        }
    }
}
