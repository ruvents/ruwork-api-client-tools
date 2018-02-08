<?php

namespace Ruwork\ApiClientTools\Result;

use PHPUnit\Framework\TestCase;
use Ruwork\ApiClientTools\Fixtures\Hydrator\TestPhpDocResult;
use Ruwork\ApiClientTools\Hydrator\PhpDocResultHydrator;

class PhpDocResultHydratorTest extends TestCase
{
    /**
     * @var PhpDocResultHydrator
     */
    private $hydrator;

    protected function setUp()
    {
        $this->hydrator = new PhpDocResultHydrator();
    }

    public function testHydrate()
    {
        $data = ['Id' => 1];

        /** @var TestPhpDocResult $result */
        $result = $this->hydrator->hydrate($data, TestPhpDocResult::class);

        $this->assertInstanceOf(TestPhpDocResult::class, $result);
        $this->assertSame(1, $result->Id);
    }

    public function testHydrateCollectionFromArray()
    {
        $data = [
            'a' => [
                ['Id' => 1],
                ['Id' => 2],
            ],
            'b' => [
                ['Id' => 3],
                'x' => ['Id' => 4],
                ['Id' => 5],
            ],
        ];

        /** @var TestPhpDocResult[][] $result */
        $result = $this->hydrator->hydrate($data, TestPhpDocResult::class.'[][]');

        $this->assertSameSize($data, $result);
        $this->assertSame(array_keys($data), array_keys($result));

        foreach ($data as $key => $dataValue) {
            $resultValue = $result[$key];

            $this->assertSameSize($dataValue, $resultValue);
            $this->assertSame(array_keys($dataValue), array_keys($resultValue));

            foreach ($dataValue as $itemKey => $itemData) {
                $this->assertSame($itemData['Id'], $resultValue[$itemKey]->Id);
            }
        }
    }

    public function testHydrateCollectionFromTraversable()
    {
        $data = new \ArrayIterator([
            [],
            [],
        ]);

        $result = $this->hydrator->hydrate($data, TestPhpDocResult::class.'[]');

        $this->assertContainsOnlyInstancesOf(TestPhpDocResult::class, $result);
    }

    public function testHydrateWithMap()
    {
        $collectionData = [[], []];

        /** @var TestPhpDocResult $result */
        $result = $this->hydrator->hydrate(['Collection' => $collectionData], TestPhpDocResult::class);

        $this->assertSameSize($collectionData, $result->Collection);
        $this->assertContainsOnlyInstancesOf(TestPhpDocResult::class, $result->Collection);
    }

    public function testHydrateFromNull()
    {
        /** @var TestPhpDocResult $result */
        $result = $this->hydrator->hydrate(null, TestPhpDocResult::class);

        $this->assertNull($result);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Class NS\NonExistingClass does not exist.
     */
    public function testNonExistingClass()
    {
        $this->hydrator->hydrate([], 'NS\NonExistingClass');
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage PHPDoc result class PHPUnit\Framework\TestCase must extend Ruwork\ApiClientTools\Hydrator\AbstractPhpDocResult.
     */
    public function testInvalidClass()
    {
        $this->hydrator->hydrate([], TestCase::class);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Expected null or array, string given.
     */
    public function testInvalidDataType()
    {
        $this->hydrator->hydrate('', TestCase::class);
    }

    /**
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Expected array or \Traversable, string given.
     */
    public function testInvalidCollectionType()
    {
        $this->hydrator->hydrate('', TestCase::class.'[]');
    }
}
