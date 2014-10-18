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

namespace Arthurh\Sphring\EventDispatcher;


use Arthurh\Sphring\Enum\SphringEventEnum;
use Arthurh\Sphring\Model\Bean;
use zpt\anno\Annotations;

/**
 * Class AnnotationsDispatcher
 * @package Arthurh\Sphring\EventDispatcher
 */
class AnnotationsDispatcher
{
    /**
     * @var Bean
     */
    private $bean;
    /**
     * @var string
     */
    private $class;

    /**
     * @var SphringEventDispatcher
     */
    private $sphringEventDispatcher;

    /**
     * @param Bean $bean
     * @param $class
     * @param SphringEventDispatcher $sphringEventDispatcher
     */
    function __construct(Bean $bean, $class, SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->bean = $bean;
        $this->class = $class;
        $this->sphringEventDispatcher = $sphringEventDispatcher;
    }


    /**
     * @return Bean
     */
    public function getBean()
    {
        return $this->bean;
    }

    /**
     * @param Bean $bean
     */
    public function setBean($bean)
    {
        $this->bean = $bean;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return SphringEventDispatcher
     */
    public function getSphringEventDispatcher()
    {
        return $this->sphringEventDispatcher;
    }

    /**
     * @param SphringEventDispatcher $sphringEventDispatcher
     */
    public function setSphringEventDispatcher($sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
    }

    /**
     *
     */
    public function dispatchAnnotations()
    {
        $classReflector = new \ReflectionClass($this->class);
        $this->dispatchEventForAnnotation($classReflector, SphringEventEnum::ANNOTATION_CLASS);
        foreach ($classReflector->getMethods() as $methodReflector) {
            $this->dispatchEventForAnnotation($methodReflector, SphringEventEnum::ANNOTATION_METHOD);
        }
    }

    /**
     * @param \Reflector $reflector
     * @param $eventNameBase
     */
    private function dispatchEventForAnnotation(\Reflector $reflector, $eventNameBase)
    {
        $annotations = new Annotations($reflector);
        $annotationsArray = $annotations->asArray();
        if (empty($annotationsArray)) {
            return;
        }
        foreach ($annotationsArray as $annotationName => $annotationValue) {
            $event = new EventAnnotation();
            $event->setData($annotationValue);
            $event->setBean($this->bean);
            $event->setReflector($reflector);
            $eventName = $eventNameBase . $annotationName;
            $event->setName($eventName);
            $this->sphringEventDispatcher->dispatch($eventName, $event);
        }
    }
}
 