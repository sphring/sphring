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
use Arthurh\Sphring\Model\Bean\AbstractBean;
use zpt\anno\Annotations;

/**
 * This class dispatch for a bean her annotation to the SphringEventDispatcher
 * Class AnnotationsDispatcher
 * @package Arthurh\Sphring\EventDispatcher
 */
class AnnotationsDispatcher
{
    /**
     * Filter list of annotation which are read by IDE or php doc
     * @var array
     */
    private $filteredAnnotation = [
        "package",
        "var",
        "return",
        "param",
        "throws",
        "deprecated",
        "link",
        "method",
        "property",
        "see",
        "inheritdoc",
        "global",
        "internal",
        "function"
    ];
    /**
     * @var AbstractBean
     *
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
     * @var mixed
     */
    private $methodArgs;

    /**
     * @var mixed
     */
    private $toReturn;

    /**
     * Constructor
     * @param AbstractBean $bean
     * @param string $class
     * @param SphringEventDispatcher $sphringEventDispatcher
     *
     */
    function __construct(AbstractBean $bean, $class, SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->bean = $bean;
        $this->class = $class;
        $this->sphringEventDispatcher = $sphringEventDispatcher;
    }

    /**
     * Return the current bean which will be dispatched
     * @return AbstractBean
     */
    public function getBean()
    {
        return $this->bean;
    }

    /**
     * Set the bean which will be dispatched
     * @param AbstractBean $bean
     */
    public function setBean($bean)
    {
        $this->bean = $bean;
    }

    /**
     * Return the class name of the object passed in the bean
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set the class name of the object passed in the bean
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * Return the SphringEventDispatcher
     * @return SphringEventDispatcher
     */
    public function getSphringEventDispatcher()
    {
        return $this->sphringEventDispatcher;
    }

    /**
     * Set the SphringEventDispatcher
     * @param SphringEventDispatcher $sphringEventDispatcher
     */
    public function setSphringEventDispatcher($sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
    }

    /**
     * Read annotations from class annotation and method annotation and dispatch events
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
     * Dispatch events
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
        $toReturn = null;
        foreach ($annotationsArray as $annotationName => $annotationValue) {
            if (in_array($annotationName, $this->filteredAnnotation)) {
                continue;
            }
            $eventName = $eventNameBase . $annotationName;

            $event = new EventAnnotation();
            $event->setData($annotationValue);
            $event->setBean($this->bean);
            $event->setReflector($reflector);
            $event->setMethodArgs($this->methodArgs);
            $event->setName($eventName);
            $this->sphringEventDispatcher->dispatch($eventName, $event);
            $this->methodArgs = $event->getMethodArgs();

            if ($annotationValue != $event->getData()) {
                $toReturn = $event->getData();
            }
        }
        $this->toReturn = $toReturn;

    }

    public function dispatchAnnotationClassInstantiate()
    {
        $classReflector = new \ReflectionClass($this->class);
        $this->dispatchEventForAnnotation($classReflector, SphringEventEnum::ANNOTATION_CLASS_CALL_INSTANTIATE);
    }

    public function dispatchAnnotationMethodCallAfter($methodName)
    {
        return $this->dispatchAnnotationMethodCall($methodName, SphringEventEnum::ANNOTATION_METHOD_CALL_AFTER);
    }

    public function dispatchAnnotationMethodCall($methodName, $eventName)
    {
        $classReflector = new \ReflectionClass($this->class);

        $this->dispatchEventForAnnotation($classReflector->getMethod($methodName), $eventName);
        if ($this->toReturn !== null) {
            return $this->toReturn;
        }
        return null;
    }

    public function dispatchAnnotationMethodCallBefore($methodName)
    {
        return $this->dispatchAnnotationMethodCall($methodName, SphringEventEnum::ANNOTATION_METHOD_CALL_BEFORE);
    }

    /**
     * Return the filtering list
     * @return array
     */
    public function getFileteredAnnotation()
    {
        return $this->filteredAnnotation;
    }

    /**
     * @return mixed
     */
    public function getMethodArgs()
    {
        return $this->methodArgs;
    }

    /**
     * @param mixed $methodArgs
     */
    public function setMethodArgs($methodArgs)
    {
        $this->methodArgs = $methodArgs;
    }

}
