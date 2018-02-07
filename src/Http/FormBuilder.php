<?php

namespace Ruwork\ApiClientTools\Http;

use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\StreamFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

final class FormBuilder extends AbstractArrayBuilder
{
    private $streamFactory;

    /**
     * @param null|array|StreamInterface|string $data
     * @param null|StreamFactory                $streamFactory
     */
    public function __construct($data = [], StreamFactory $streamFactory = null)
    {
        if (!is_array($data)) {
            $data = (string) $data;
            parse_str($data, $data);
        }

        parent::__construct($data);
        $this->streamFactory = $streamFactory ?: StreamFactoryDiscovery::find();
    }

    /**
     * @return string
     */
    public function build()
    {
        return http_build_query($this->data, '', '&');
    }

    /**
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    public function applyToRequest(RequestInterface $request)
    {
        return $request
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withBody($this->streamFactory->createStream($this->build()));
    }
}
