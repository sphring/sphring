<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 15/10/2014
 */

namespace Arthurh\Sphring\Annotation;


use Arthurh\Sphring\AbstractTestSphring;
use Arthurh\Sphring\Sphring;

class AnnotationTest extends AbstractTestSphring
{
    const REQUIRED_ANNOTATION_FILE = 'annotation/mainRequiredAnnotation.yml';

    /**
     * @expectedException \Arthurh\Sphring\Exception\SphringAnnotationException
     */
    public function testRequired()
    {
        $sphring = new Sphring(self::$CONTEXT_FOLDER . '/' . self::REQUIRED_ANNOTATION_FILE);
        $sphring->loadContext();
    }
} 