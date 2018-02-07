<?php

namespace Ruwork\ApiClientTools\Fixtures\Hydrator;

use Ruwork\ApiClientTools\Hydrator\AbstractDocBlockResult;

/**
 * @property int                     $Id
 * @property string                  $Name
 * @property null|TestDocBlockResult $Child
 * @property TestDocBlockResult[]    $Collection
 */
class TestDocBlockResult extends AbstractDocBlockResult
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
