<?php

namespace Ruwork\ApiClientTools\ResponseDecoder\Exception;

final class JsonDecodeException extends \RuntimeException
{
    private $invalidString;

    /**
     * @param string $invalidString
     */
    public function __construct($invalidString)
    {
        parent::__construct(json_last_error_msg(), json_last_error());
        $this->invalidString = $invalidString;
    }

    /**
     * @return string
     */
    public function getInvalidString()
    {
        return $this->invalidString;
    }
}
