<?php
/**
 * Copyright (C) 2015 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 18/03/2015
 */


namespace Arthurh\Sphring\Model\Annotation;


use Arthurh\Sphring\Annotations\AnnotationsSphring\MethodInit;
use Arthurh\Sphring\Exception\SphringAnnotationException;
use Arthurh\Sphring\Utils\ClassName;

class MethodInitAnnotation extends AbstractAnnotation
{
    /**
     * @return string
     */
    public static function getAnnotationName()
    {
        return ClassName::getShortName(MethodInit::class);
    }

    /**
     * @return mixed
     * @throws SphringAnnotationException
     */
    public function run()
    {
        $methodName = $this->reflector->getName();
        $this->getBean()->getObject()->$methodName();
    }
}
