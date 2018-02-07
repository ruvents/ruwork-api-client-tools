<?php

namespace Ruwork\ApiClientTools\Fixtures\Facade;

use Ruwork\ApiClientTools\Facade\AbstractFacade;

class TestFacade extends AbstractFacade
{
    protected $options = [
        'method' => 'GET',
        'endpoint' => '/test',
    ];
}
