<?php

namespace Ruwork\ApiClientTools\RequestProcessor;

use Psr\Http\Message\RequestInterface;

interface RequestProcessorInterface
{
    /**
     * @param RequestInterface $request
     *
     * @return mixed
     */
    public function process(RequestInterface $request);
}
