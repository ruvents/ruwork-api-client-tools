<?php

namespace Ruwork\ApiClientTools\Fixtures\Hydrator;

use Ruwork\ApiClientTools\Hydrator\AbstractPhpDocResult;

/**
 * @property int                   $Id
 * @property string                $Name
 * @property null|TestPhpDocResult $Child
 * @property TestPhpDocResult[]    $Collection
 */
class TestPhpDocResult extends AbstractPhpDocResult
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
