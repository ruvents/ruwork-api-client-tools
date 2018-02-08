<?php

namespace Ruwork\ApiClientTools\RequestFactory;

use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestFactoryTest extends TestCase
{
    public function testCreateGetRequest()
    {
        $request = $this->createRequest([
            'method' => 'get',
            'endpoint' => '/test',
            'headers' => [
                'h1' => 1,
            ],
            'data' => [
                'q1' => 1,
            ],
        ]);

        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/test?q1=1', (string) $request->getUri());
        $this->assertSame('1', $request->getHeaderLine('h1'));
        $this->assertSame('', (string) $request->getBody());
    }

    public function testCreatePutRequest()
    {
        $request = $this->createRequest([
            'method' => 'pUt',
            'endpoint' => '/put',
            'headers' => [
                'h1' => 1,
            ],
            'data' => [
                'f1' => 1,
            ],
        ]);

        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('/put', (string) $request->getUri());
        $this->assertSame('1', $request->getHeaderLine('h1'));
        $this->assertSame('f1=1', (string) $request->getBody());
    }

    private function createRequest(array $options)
    {
        $resolver = new OptionsResolver();
        $factory = new RequestFactory();
        $factory->configureOptions($resolver);
        $options = $resolver->resolve($options);

        return $factory->createRequest($options);
    }
}
