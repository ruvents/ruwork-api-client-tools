<?php

namespace Ruwork\ApiClientTools\Http;

use Psr\Http\Message\RequestInterface;

final class HeadersBuilder extends AbstractArrayBuilder
{
    /**
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    public function applyToRequest(RequestInterface $request)
    {
        foreach ($this->data as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        return $request;
    }
}
