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
 * @Target({"CLASS"})
 * Class RootProject
 * @package Arthurh\Sphring\Annotations\AnnotationsSphring
 */
class RootProject
{
    /**
     * @var string
     */
    public $file;
}
