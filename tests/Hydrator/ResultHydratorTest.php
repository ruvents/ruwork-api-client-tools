<?php

namespace Ruwork\ApiClientTools\Result;

use PHPUnit\Framework\TestCase;
use Ruwork\ApiClientTools\Fixtures\Hydrator\TestResult;
use Ruwork\ApiClientTools\Hydrator\ResultHydrator;

class ResultHydratorTest extends TestCase
{
    /**
     * @var ResultHydrator
     */
    private $hydrator;

    protected function setUp()
    {
        $this->hydrator = new ResultHydrator();
    }

    public function testHydrate()
    {
        $data = ['Id' => 1];

        /** @var TestResult $result */
        $result = $this->hydrator->hydrate($data, TestResult::class);

        $this->assertInstanceOf(TestResult::class, $result);
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

        /** @var TestResult[][] $result */
        $result = $this->hydrator->hydrate($data, TestResult::class.'[][]');

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

        $result = $this->hydrator->hydrate($data, TestResult::class.'[]');

        $this->assertContainsOnlyInstancesOf(TestResult::class, $result);
    }

    public function testHydrateWithMap()
    {
        $collectionData = [[], []];

        /** @var TestResult $result */
        $result = $this->hydrator->hydrate(['Collection' => $collectionData], TestResult::class);

        $this->assertSameSize($collectionData, $result->Collection);
        $this->assertContainsOnlyInstancesOf(TestResult::class, $result->Collection);
    }

    public function testHydrateFromNull()
    {
        /** @var TestResult $result */
        $result = $this->hydrator->hydrate(null, TestResult::class);

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
     * @expectedExceptionMessage Result class PHPUnit\Framework\TestCase must extend Ruwork\ApiClientTools\Hydrator\AbstractResult.
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
