<?php

namespace Ruwork\ApiClientTools\ResponseDecoder;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Ruwork\ApiClientTools\Exception\JsonDecodeException;

class JsonResponseDecoderTest extends TestCase
{
    public function testDecode()
    {
        $decoder = new JsonResponseDecoder();

        $data = ['a' => 1, 'b' => ['x' => 'y']];
        $respone = new Response(200, [], json_encode($data));
        $actual = $decoder->decode($respone);

        $this->assertSame($data, $actual);
    }

    public function testDecodeNonAssoc()
    {
        $decoder = new JsonResponseDecoder(false);

        $data = ['a' => 1];
        $respone = new Response(200, [], json_encode($data));
        $actual = $decoder->decode($respone);

        $this->assertInstanceOf(\stdClass::class, $actual);
        $this->assertSame(1, $actual->a);
    }

    public function testJsonDecodeException()
    {
        $invalidString = 'non-json';
        $decoder = new JsonResponseDecoder();

        try {
            $decoder->decode(new Response(200, [], $invalidString));
            $this->fail('No exception thrown.');
        } catch (JsonDecodeException $exception) {
            $this->assertSame('Syntax error', $exception->getMessage());
            $this->assertSame(JSON_ERROR_SYNTAX, $exception->getCode());
            $this->assertSame($invalidString, $exception->getInvalidString());
        }
    }
}
