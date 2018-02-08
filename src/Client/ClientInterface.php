<?php

namespace Ruwork\ApiClientTools\Client;

use Psr\Http\Message\RequestInterface;

interface ClientInterface
{
    /**
     * @param RequestInterface $request
     *
     * @return mixed
     */
    public function request(RequestInterface $request);
}
