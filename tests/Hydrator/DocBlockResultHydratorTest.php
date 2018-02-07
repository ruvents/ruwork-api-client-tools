<?php

namespace Ruwork\ApiClientTools\Result;

use PHPUnit\Framework\TestCase;
use Ruwork\ApiClientTools\Fixtures\Hydrator\TestDocBlockResult;
use Ruwork\ApiClientTools\Hydrator\DocBlockResultHydrator;

class DocBlockResultHydratorTest extends TestCase
{
    /**
     * @var DocBlockResultHydrator
     */
    private $hydrator;

    protected function setUp()
    {
        $this->hydrator = new DocBlockResultHydrator();
    }

    public function testHydrate()
    {
        $data = ['Id' => 1];

        /** @var TestDocBlockResult $result */
        $result = $this->hydrator->hydrate($data, TestDocBlockResult::class);

        $this->assertInstanceOf(TestDocBlockResult::class, $result);
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

        /** @var TestDocBlockResult[][] $result */
        $result = $this->hydrator->hydrate($data, TestDocBlockResult::class.'[][]');

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

        $result = $this->hydrator->hydrate($data, TestDocBlockResult::class.'[]');

        $this->assertContainsOnlyInstancesOf(TestDocBlockResult::class, $result);
    }

    public function testHydrateWithMap()
    {
        $collectionData = [[], []];

        /** @var TestDocBlockResult $result */
        $result = $this->hydrator->hydrate(['Collection' => $collectionData], TestDocBlockResult::class);

        $this->assertSameSize($collectionData, $result->Collection);
        $this->assertContainsOnlyInstancesOf(TestDocBlockResult::class, $result->Collection);
    }

    public function testHydrateFromNull()
    {
        /** @var TestDocBlockResult $result */
        $result = $this->hydrator->hydrate(null, TestDocBlockResult::class);

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
     * @expectedExceptionMessage DocBlock result class PHPUnit\Framework\TestCase must extend Ruwork\ApiClientTools\Hydrator\AbstractDocBlockResult.
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
