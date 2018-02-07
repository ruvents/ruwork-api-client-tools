<?php

namespace Ruwork\ApiClientTools\Http;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

class HeadersBuilderTest extends TestCase
{
    public function testConstructFromArray()
    {
        $this->assertSame(['a' => '1'], (new HeadersBuilder(['a' => '1']))->getData());
    }

    public function testApplyToRequest()
    {
        $builder = new HeadersBuilder(['a' => 1]);
        $request = $builder->applyToRequest(new Request('GET', '/'));

        $this->assertSame('1', $request->getHeaderLine('a'));
    }
}
