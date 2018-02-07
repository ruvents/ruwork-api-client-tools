<?php

namespace Ruwork\ApiClientTools\Result;

use PHPUnit\Framework\TestCase;
use Ruwork\ApiClientTools\Fixtures\Hydrator\IteratorAggregate;
use Ruwork\ApiClientTools\Hydrator\TraversableCache;

class TraversableCacheTest extends TestCase
{
    public function testFullIteration()
    {
        $expectedValues = range(1, 10);

        $generator = $this->generateObjects($expectedValues);
        $iterator = new TraversableCache(new IteratorAggregate($generator));

        $actualPartialValues = [];

        foreach ($iterator as $key => $item) {
            $actualPartialValues[$key] = $item;

            if (4 === $item->value) {
                break;
            }
        }

        $result = iterator_to_array($iterator);
        $result2 = iterator_to_array($iterator);

        $this->assertSame(array_slice($result, 0, count($actualPartialValues)), $actualPartialValues);
        $this->assertSame($result, $result2);

        $actualValues = array_column($result, 'value');

        $this->assertSame($expectedValues, $actualValues);
    }

    public function generateObjects(array $values)
    {
        foreach ($values as $value) {
            $obj = new \stdClass();
            $obj->value = $value;
            yield $obj;
        }
    }
}
