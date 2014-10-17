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

namespace Arthurh\Sphring\FakeExtend;


use Arthurh\Sphring\Model\Annotation\RequiredAnnotation;

class AnnotationRequiredExtend extends RequiredAnnotation
{
    public function run()
    {
        parent::run();
        $this->getBean()->getObject()->testExtend = 'testAnnotationExtend';
    }
} 