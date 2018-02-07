<?php

namespace Ruwork\ApiClientTools\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

final class QueryBuilder extends AbstractArrayBuilder
{
    /**
     * @param array|string $data
     */
    public function __construct($data = [])
    {
        if (is_string($data)) {
            parse_str($data, $data);
        }

        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function build()
    {
        return http_build_query($this->data, '', '&');
    }

    /**
     * @param UriInterface $uri
     *
     * @return UriInterface
     */
    public function applyToUri(UriInterface $uri)
    {
        parse_str($uri->getQuery(), $uriQuery);
        $query = array_replace($uriQuery, $this->data);

        return $uri->withQuery(http_build_query($query, '', '&'));
    }

    /**
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    public function applyToRequest(RequestInterface $request)
    {
        return $request->withUri($this->applyToUri($request->getUri()));
    }
}
