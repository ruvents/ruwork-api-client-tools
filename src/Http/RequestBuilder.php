<?php

namespace Ruwork\ApiClientTools\Http;

use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;
use Psr\Http\Message\RequestInterface;

class RequestBuilder
{
    private static $formMethods = [
        'POST' => true,
        'PUT' => true,
        'PATCH' => true,
    ];

    private $requestFactory;
    private $streamFactory;
    private $method = 'GET';
    private $path;

    /**
     * @var null|HeadersBuilder
     */
    private $headersBuilder;

    /**
     * @var null|QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var null|FormBuilder
     */
    private $formBuilder;

    public function __construct(
        RequestFactory $requestFactory = null,
        StreamFactory $streamFactory = null
    ) {
        $this->requestFactory = $requestFactory ?: MessageFactoryDiscovery::find();
        $this->streamFactory = $streamFactory ?: StreamFactoryDiscovery::find();
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return HeadersBuilder
     */
    public function getHeadersBuilder()
    {
        if (null === $this->headersBuilder) {
            $this->headersBuilder = new HeadersBuilder();
        }

        return $this->headersBuilder;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        if (null === $this->queryBuilder) {
            $this->queryBuilder = new QueryBuilder();
        }

        return $this->queryBuilder;
    }

    /**
     * @return FormBuilder
     */
    public function getFormBuilder()
    {
        if (null === $this->formBuilder) {
            $this->formBuilder = new FormBuilder([], $this->streamFactory);
        }

        return $this->formBuilder;
    }

    /**
     * @return ArrayBuilderInterface
     */
    public function getDataBuilder()
    {
        if (isset(self::$formMethods[$this->method])) {
            return $this->getFormBuilder();
        }

        return $this->getQueryBuilder();
    }

    /**
     * @return RequestInterface
     */
    public function build()
    {
        if (null === $this->path) {
            throw new \RuntimeException('Path was not set. Use setPath().');
        }

        $request = $this->requestFactory->createRequest($this->method, $this->path);

        if (null !== $this->headersBuilder) {
            $request = $this->headersBuilder->applyToRequest($request);
        }

        if (null !== $this->queryBuilder) {
            $request = $this->queryBuilder->applyToRequest($request);
        }

        if (null !== $this->formBuilder) {
            $request = $this->formBuilder->applyToRequest($request);
        }

        return $request;
    }
}
