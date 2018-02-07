<?php

namespace Ruwork\ApiClientTools\Http;

use PHPUnit\Framework\TestCase;

class AbstractArrayBuilderTest extends TestCase
{
    public function testSettersAndGetters()
    {
        $data = ['a' => 1];
        $builder = $this->getMockForAbstractClass(AbstractArrayBuilder::class, [$data]);

        $this->assertSame($data, $builder->getData());

        $builder
            ->setData([
                'a' => 1,
                'b' => 1,
                'c' => 3,
            ])
            ->setValue('b', 2);

        $this->assertSame($builder->getData(), ['a' => 1, 'b' => 2, 'c' => 3]);
        $this->assertSame(1, $builder->getValue('a'));
        $this->assertNull($builder->getValue('z'));
        $this->assertSame(10, $builder->getValue('z', 10));
    }
}
