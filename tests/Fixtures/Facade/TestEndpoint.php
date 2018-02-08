<?php

namespace Ruwork\ApiClientTools\Fixtures\Facade;

use Ruwork\ApiClientTools\Facade\AbstractEndpoint;
use Ruwork\ApiClientTools\Fixtures\Hydrator\TestDocBlockResult;

class TestEndpoint extends AbstractEndpoint
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->options = [
            'endpoint' => '/test',
        ];
        $this->class = TestDocBlockResult::class;
    }
}
