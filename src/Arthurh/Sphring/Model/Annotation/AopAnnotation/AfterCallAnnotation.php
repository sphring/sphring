<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 13/03/2015
 */

namespace Arthurh\Sphring\Model\Annotation\AopAnnotation;


use Arthurh\Sphring\Annotations\AnnotationsSphring\AfterCall;
use Arthurh\Sphring\Utils\ClassName;

class AfterCallAnnotation extends CallAnnotation
{
    /**
     * @return string
     */
    public static function getAnnotationName()
    {
        return ClassName::getShortName(AfterCall::class);
    }
}
