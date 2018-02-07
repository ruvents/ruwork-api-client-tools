<?php

namespace Ruwork\ApiClientTools\Http;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    public function testConstructFromArray()
    {
        $this->assertSame(['a' => '1'], (new QueryBuilder(['a' => '1']))->getData());
    }

    public function testConstructFromString()
    {
        $this->assertSame(['a' => '1'], (new QueryBuilder('a=1'))->getData());
    }

    public function testBuild()
    {
        $this->assertSame('a=1', (new QueryBuilder(['a' => '1']))->build());
    }

    public function testApplyToUri()
    {
        $builder = new QueryBuilder(['b' => 2]);
        $uri = $builder->applyToUri(new Uri('/?a=1'));

        $this->assertSame('/?a=1&b=2', (string) $uri);
    }

    public function testApplyToRequest()
    {
        $builder = new QueryBuilder(['b' => 2]);
        $request = $builder->applyToRequest(new Request('GET', '/?a=1'));

        $this->assertSame('/?a=1&b=2', (string) $request->getUri());
    }
}
