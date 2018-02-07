<?php

namespace Ruwork\ApiClientTools\Http;

use PHPUnit\Framework\TestCase;

class RequestBuilderTest extends TestCase
{
    public function testGetMethod()
    {
        $builder = (new RequestBuilder())->setMethod('POST');
        $this->assertSame('POST', $builder->getMethod());
    }

    public function testGetPath()
    {
        $builder = (new RequestBuilder())->setPath('/path');
        $this->assertSame('/path', $builder->getPath());
    }

    public function testGetDataBuilder()
    {
        $builder = new RequestBuilder();
        $queryBuilder = $builder->getQueryBuilder();
        $formBuilder = $builder->getFormBuilder();

        $this->assertSame($queryBuilder, $builder->getDataBuilder());
        $this->assertSame($queryBuilder, $builder->setMethod('GET')->getDataBuilder());
        $this->assertSame($queryBuilder, $builder->setMethod('DELETE')->getDataBuilder());
        $this->assertSame($formBuilder, $builder->setMethod('POST')->getDataBuilder());
        $this->assertSame($formBuilder, $builder->setMethod('PUT')->getDataBuilder());
        $this->assertSame($formBuilder, $builder->setMethod('PATCH')->getDataBuilder());
    }

    public function testBuild()
    {
        $builder = (new RequestBuilder())
            ->setMethod('PUT')
            ->setPath('/put');

        $builder->getHeadersBuilder()
            ->setValue('h1', 1);

        $builder->getQueryBuilder()
            ->setValue('q1', 1);

        $builder->getFormBuilder()
            ->setValue('f1', 1);

        $request = $builder->build();

        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('/put', $request->getUri()->getPath());
        $this->assertSame('1', $request->getHeaderLine('h1'));
        $this->assertSame('q1=1', $request->getUri()->getQuery());
        $this->assertSame('f1=1', (string) $request->getBody());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Path was not set. Use setPath().
     */
    public function testNoPathException()
    {
        (new RequestBuilder())->build();
    }
}
