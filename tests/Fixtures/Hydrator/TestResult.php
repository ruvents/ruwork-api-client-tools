<?php

namespace Ruwork\ApiClientTools\Fixtures\Hydrator;

use Ruwork\ApiClientTools\Hydrator\AbstractResult;

/**
 * @property int             $Id
 * @property string          $Name
 * @property null|TestResult $Child
 * @property TestResult[]    $Collection
 */
class TestResult extends AbstractResult
{
    /**
     * {@inheritdoc}
     */
    public static function getMap()
    {
        return [
            'Collection' => self::class.'[]',
        ];
    }
}
