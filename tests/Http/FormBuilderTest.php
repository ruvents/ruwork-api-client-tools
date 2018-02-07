<?php

namespace Ruwork\ApiClientTools\Http;

use GuzzleHttp\Psr7\Request;
use Http\Discovery\StreamFactoryDiscovery;
use PHPUnit\Framework\TestCase;

class FormBuilderTest extends TestCase
{
    public function testConstructFromArray()
    {
        $this->assertSame(['a' => '1'], (new FormBuilder(['a' => '1']))->getData());
    }

    public function testConstructFromString()
    {
        $this->assertSame(['a' => '1'], (new FormBuilder('a=1'))->getData());
    }

    public function testConstructFromNull()
    {
        $this->assertSame([], (new FormBuilder(null))->getData());
    }

    public function testConstructFromStream()
    {
        $stream = StreamFactoryDiscovery::find()->createStream('a=1');
        $this->assertSame(['a' => '1'], (new FormBuilder($stream))->getData());
    }

    public function testBuild()
    {
        $this->assertSame('a=1', (new FormBuilder(['a' => '1']))->build());
    }

    public function testApplyToRequest()
    {
        $builder = new FormBuilder(['a' => 1]);
        $request = $builder->applyToRequest(new Request('GET', '/'));

        $this->assertSame('application/x-www-form-urlencoded', $request->getHeaderLine('Content-Type'));
        $this->assertSame('a=1', (string) $request->getBody());
    }
}
