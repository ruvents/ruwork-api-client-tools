<?php

namespace Ruwork\ApiClientTools\ResponseDecoder;

use Psr\Http\Message\ResponseInterface;
use Ruwork\ApiClientTools\ResponseDecoder\Exception\JsonDecodeException;

class JsonResponseDecoder implements ResponseDecoderInterface
{
    private $associative;
    private $depth;
    private $options;

    /**
     * @param bool $associative
     * @param int  $depth
     * @param int  $options
     */
    public function __construct($associative = true, $depth = 512, $options = 0)
    {
        $this->associative = $associative;
        $this->depth = $depth;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function decodeResponse(ResponseInterface $response)
    {
        $string = (string) $response->getBody();
        $data = json_decode($string, $this->associative, $this->depth, $this->options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonDecodeException($string);
        }

        return $data;
    }
}
