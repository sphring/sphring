<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 18/10/2014
 */

namespace Arthurh\Sphring\Model\Annotation;

use Arthurh\Sphring\Enum\SphringEventEnum;

/**
 * Class BeforeStartMethodOnSphringEventAnnotation
 * @package Arthurh\Sphring\Model\Annotation
 *
 */
class BeforeStartMethodOnSphringEventAnnotation extends AbstractMethodOnSphringEventAnnotation
{
    /**
     * @return string
     */
    public static function getAnnotationName()
    {
        return "BeforeStart";
    }

    /**
     * @return string
     */
    function getEventSphring()
    {
        return SphringEventEnum::SPHRING_START_LOAD;
    }
}
