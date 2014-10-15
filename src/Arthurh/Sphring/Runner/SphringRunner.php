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

namespace Arthurh\Sphring\Runner;


use Arthurh\Sphring\Enum\SphringEventEnum;
use Arthurh\Sphring\EventDispatcher\EventAnnotation;
use Arthurh\Sphring\Sphring;
use zpt\anno\Annotations;

abstract class SphringRunner
{
    /**
     * @var SphringRunner
     */
    protected static $_instance;
    /**
     * @var Sphring
     */
    private $sphring;

    protected function __construct()
    {
        $this->sphring = new Sphring();
        $this->dispatchAnnotations();
    }

    /**
     * @return SphringRunner
     */
    public final static function getInstance()
    {
        if (null === static::$_instance) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }

    private function dispatchAnnotations()
    {
        $classReflector = new \ReflectionClass(get_class($this));
        $this->dispatchEventForAnnotation($classReflector, SphringEventEnum::ANNOTATION_CLASS);
        foreach ($classReflector->getMethods() as $methodReflector) {
            $this->dispatchEventForAnnotation($methodReflector, SphringEventEnum::ANNOTATION_METHOD);
        }
    }

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
            $event->setReflector($reflector);

            $eventName = $eventNameBase . $annotationName;
            $event->setName($eventName);
            $event = $this->sphring->getSphringEventDispatcher()->dispatch($eventName, $event);
        }
    }

    /**
     * @return Sphring
     */
    public function getSphring()
    {
        return $this->sphring;
    }

    /**
     * @param Sphring $sphring
     */
    public function setSphring($sphring)
    {
        $this->sphring = $sphring;
    }

    public function getBean($beanId)
    {
        return $this->sphring->getBean($beanId);
    }
} 