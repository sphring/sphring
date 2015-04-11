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

namespace Arthurh\Sphring\Annotations\AnnotationsSphring;

/**
 * @Annotation
 * @Target({"METHOD"})
 * Class AfterCall
 * @package Arthurh\Sphring\Annotations\AnnotationsSphring
 */
class AfterCall
{
    /**
     * @var string
     */
    public $bean;

    /**
     * @var string
     */
    public $method;

    /**
     * @var boolean
     */
    public $return;

    /**
     * @var string
     */
    public $condition;
}
