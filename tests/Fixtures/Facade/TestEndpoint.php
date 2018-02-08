<?php

namespace Ruwork\ApiClientTools\Fixtures\Facade;

use Ruwork\ApiClientTools\Facade\AbstractEndpoint;
use Ruwork\ApiClientTools\Fixtures\Hydrator\TestPhpDocResult;

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
        $this->class = TestPhpDocResult::class;
    }
}
