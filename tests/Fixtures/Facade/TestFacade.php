<?php

namespace Ruwork\ApiClientTools\Fixtures\Facade;

use Ruwork\ApiClientTools\Facade\AbstractFacade;

class TestFacade extends AbstractFacade
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->options = [
            'endpoint' => '/test',
        ];
    }
}
