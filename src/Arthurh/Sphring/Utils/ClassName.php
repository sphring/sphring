<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 11/04/2015
 */

namespace Arthurh\Sphring\Utils;


class ClassName
{
    public static function getShortName($className)
    {
        if (is_object($className)) {
            $className = get_class($className);
        }
        $explodedClassName = explode('\\', $className);
        return $explodedClassName[count($explodedClassName) - 1];
    }
}
