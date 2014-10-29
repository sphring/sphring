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

use Arthurh\Sphring\Exception\SphringAnnotationException;
use Arthurh\Sphring\Model\Bean\Bean;

/**
 * Class AutoWireAnnotation
 * @package Arthurh\Sphring\Model\Annotation
 */
class AutoWireAnnotation extends AbstractAnnotation
{

    /**
     * @throws \Arthurh\Sphring\Exception\SphringAnnotationException
     * @return mixed
     */
    public function run()
    {
        $reflector = $this->reflector;
        if (!($reflector instanceof \ReflectionMethod)) {

            return;
        }

        if (!$this->isSetter()) {
            throw new SphringAnnotationException("Error for bean '%s', you can set autowire only on a setter.", $this->bean->getId());
        }
        $parameterClass = $this->getParameterClass();
        $validBeans = $this->getValidBeans($parameterClass);
        $nbValidBean = count($validBeans);
        if ($nbValidBean !== 1) {
            throw new SphringAnnotationException("Error for bean '%s', there is '%s' beans matching your autowire for method '%s'.",
                $this->bean->getId(), $nbValidBean, $reflector->name);
        }
        $validBean = $validBeans[0];
        $methodName = $reflector->name;
        $this->bean->getObject()->$methodName($validBean->getObject());
    }

    /**
     * @param null|\ReflectionClass $parameterClass
     * @return Bean[]
     */
    public function getValidBeans($parameterClass)
    {
        $validBeans = [];

        $beans = $this->getSphringEventDispatcher()->getSphring()->getBeansObject();
        foreach ($beans as $bean) {
            if ($bean->containClassName($parameterClass)) {
                $validBeans[] = $bean;
            }
        }
        return $validBeans;
    }

    /**
     * @return null|string
     * @throws \Arthurh\Sphring\Exception\SphringAnnotationException
     */
    public function getParameterClass()
    {
        $reflector = $this->reflector;
        if (!($reflector instanceof \ReflectionMethod)) {
            return null;
        }
        $nbParameters = count($reflector->getParameters());
        if ($nbParameters !== 1) {
            throw new SphringAnnotationException("Error for bean '%s', you must have one parameter for autowire method '%s'.",
                $this->bean->getId(), $reflector->name);
        }
        $parameterClass = $reflector->getParameters()[0]->getClass();
        if (empty($parameterClass)) {
            throw new SphringAnnotationException("Error for bean '%s', you must force type for autowire method '%s'. Example: '%s'(Object \$object)",
                $this->bean->getId(), $reflector->name);
        }
        return $parameterClass->name;
    }

    /**
     * @return string
     */
    public static function getAnnotationName()
    {
        return "AutoWire";
    }
}
