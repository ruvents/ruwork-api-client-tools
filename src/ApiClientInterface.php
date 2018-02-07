<?php

namespace Ruwork\ApiClientTools;

use Psr\Http\Message\RequestInterface;

interface ApiClientInterface
{
    /**
     * @param RequestInterface $request
     *
     * @return mixed
     */
    public function request(RequestInterface $request);
}
