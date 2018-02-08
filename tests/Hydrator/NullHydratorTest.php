<?php

namespace Ruwork\ApiClientTools\Result;

use PHPUnit\Framework\TestCase;
use Ruwork\ApiClientTools\Hydrator\NullHydrator;

class NullHydratorTest extends TestCase
{
    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Hydrating is not supported.
     */
    public function testException()
    {
        (new NullHydrator())->hydrate([], self::class);
    }
}
