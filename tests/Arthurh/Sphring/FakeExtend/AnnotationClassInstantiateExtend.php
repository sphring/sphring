<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 14/03/2015
 */

namespace Arthurh\Sphring\FakeExtend;


use Arthurh\Sphring\Exception\SphringAnnotationException;
use Arthurh\Sphring\Model\Annotation\AbstractAnnotation;

class AnnotationClassInstantiateExtend extends AbstractAnnotation
{

    public function run()
    {
        if (!$this->isClass()) {
            throw new SphringAnnotationException("Error for bean '%s', you can set %s only on a class.", $this->bean->getId(), self::getAnnotationName());
        }
        $this->getBean()->getObject()->setTestingValue(true);
    }

    /**
     * @return string
     */
    public static function getAnnotationName()
    {
        return "TestClassInstantiate";
    }
}
