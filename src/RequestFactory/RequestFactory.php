<?php

namespace Ruwork\ApiClientTools\RequestFactory;

use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\RequestFactory as HttpRequestFactory;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestFactory implements RequestFactoryInterface
{
    private $requestFactory;

    public function __construct(HttpRequestFactory $requestFactory = null)
    {
        $this->requestFactory = $requestFactory ?: MessageFactoryDiscovery::find();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired([
                'endpoint',
            ])
            ->setDefaults([
                'method' => 'GET',
                'headers' => [],
                'data' => [],
            ])
            ->setAllowedTypes('method', 'string')
            ->setAllowedTypes('endpoint', 'string')
            ->setAllowedTypes('headers', 'array')
            ->setAllowedTypes('data', 'array')
            ->setNormalizer('method', function (Options $options, $method) {
                return strtoupper($method);
            });
    }

    /**
     * {@inheritdoc}
     */
    public function createRequest(array $options)
    {
        $uri = $options['endpoint'];
        $method = $options['method'];
        $encodedData = http_build_query($options['data'], '', '&');
        $headers = $options['headers'];
        $body = null;

        if (in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
            $body = $encodedData;
        } else {
            $uri .= '?'.$encodedData;
        }

        return $this->requestFactory->createRequest($method, $uri, $headers, $body);
    }
}
