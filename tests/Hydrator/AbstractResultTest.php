<?php

namespace Ruwork\ApiClientTools\Result;

use PHPUnit\Framework\TestCase;
use Ruwork\ApiClientTools\Fixtures\Hydrator\TestDocBlockResult;

class AbstractResultTest extends TestCase
{
    public function testMagicPropertyAccess()
    {
        $result = new TestDocBlockResult(['Id' => 1, 'Name' => null]);

        $this->assertTrue($result->exists('Id'));
        $this->assertTrue(isset($result->Id));
        $this->assertSame(1, $result->Id);

        $this->assertTrue($result->exists('Name'));
        $this->assertFalse(isset($result->Name));
        $this->assertNull($result->Name);

        $this->assertFalse($result->exists('Collection'));
        $this->assertFalse(isset($result->Collection));
        $this->assertNull($result->Collection);
    }

    public function testIterator()
    {
        $data = ['Id' => 1, 'Name' => null];
        $this->assertSame($data, iterator_to_array(new TestDocBlockResult($data)));
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage This object is immutable.
     */
    public function testMagicSetException()
    {
        $result = new TestDocBlockResult([]);
        $result->Id = 1;
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage This object is immutable.
     */
    public function testMagicUnsetException()
    {
        $result = new TestDocBlockResult([]);
        unset($result->Id);
    }
}
