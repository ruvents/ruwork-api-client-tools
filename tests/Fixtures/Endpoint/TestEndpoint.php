<?php

namespace Ruwork\ApiClientTools\Fixtures\Endpoint;

use Ruwork\ApiClientTools\Endpoint\AbstractEndpoint;

class TestEndpoint extends AbstractEndpoint
{
    public function getRequestBuilder()
    {
        return $this->requestBuilder;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->requestBuilder
            ->setPath('/test');
    }
}
