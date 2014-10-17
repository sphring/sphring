<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 14/10/2014
 */

namespace Arthurh\Sphring\Enum;


use MyCLabs\Enum\Enum;

class BeanTypeEnum extends Enum
{
    const ABSTRACT_TYPE = 'abstract';
    const NORMAL_TYPE = 'normal';

    public static function  fromValue($value)
    {
        $constants = self::toArray();
        foreach ($constants as $constantName => $constantValue) {
            if ($constantValue == $value) {
                return self::$constantName();
            }
        }
        return null;
    }
}
