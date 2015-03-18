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


use Arthurh\Sphring\Exception\SphringAnnotationException;

class MethodInitAnnotation extends AbstractAnnotation
{
    /**
     * @return string
     */
    public static function getAnnotationName()
    {
        return "MethodInit";
    }

    /**
     * @return mixed
     * @throws SphringAnnotationException
     */
    public function run()
    {
        if (!$this->isMethod()) {
            throw new SphringAnnotationException("Error for bean '%s', you can set MthodInit only on a method.", $this->bean->getId());
        }
        $methodName = $this->reflector->getName();
        $this->getBean()->getObject()->$methodName();
    }
}
