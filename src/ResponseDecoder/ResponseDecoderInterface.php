<?php

namespace Ruwork\ApiClientTools\ResponseDecoder;

use Psr\Http\Message\ResponseInterface;

interface ResponseDecoderInterface
{
    /**
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public function decode(ResponseInterface $response);
}
