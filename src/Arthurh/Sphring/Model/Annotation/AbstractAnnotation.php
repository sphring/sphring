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

namespace Arthurh\Sphring\Model\Annotation;

use Arthurh\Sphring\EventDispatcher\EventAnnotation;
use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Model\Bean\Bean;
use Arthurh\Sphring\Runner\SphringRunner;

/**
 * Class AbstractAnnotation
 * @package Arthurh\Sphring\Model\Annotation
 */
abstract class AbstractAnnotation
{
    /**
     * @var SphringEventDispatcher
     */
    protected $sphringEventDispatcher;
    /**
     * @var \Reflector
     */
    protected $reflector;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var Bean
     */
    protected $bean;
    /**
     * @var EventAnnotation
     */
    protected $event;

    /**
     * @return string
     */
    public static function getAnnotationName()
    {
        $className = self::class;
        $className = explode('\\', $className);
        $className = $className[count($className) - 1];
        return $className;
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
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Reflector
     */
    public function getReflector()
    {
        return $this->reflector;
    }

    /**
     * @param \Reflector $reflector
     */
    public function setReflector($reflector)
    {
        $this->reflector = $reflector;
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
     * @return mixed
     */
    abstract public function run();

    /**
     * @return bool
     */
    protected function isSetter()
    {
        if (!$this->isMethod()) {
            return false;
        }
        $name = $this->reflector->getName();
        $parse = substr($name, 0, 3);
        return $parse == 'set';
    }

    /**
     * @return bool
     */
    protected function isGetter()
    {
        if (!$this->isMethod()) {
            return false;
        }
        $name = $this->reflector->getName();
        $parse = substr($name, 0, 3);
        return $parse == 'get';
    }

    /**
     * @return bool
     */
    protected function isClass()
    {
        return $this->reflector instanceof \ReflectionClass;
    }

    /**
     * @return bool
     */
    protected function isMethod()
    {
        return $this->reflector instanceof \ReflectionMethod;
    }

    /**
     * @return bool
     */
    protected function isInSphringRunner()
    {

        return $this->bean->getObject() instanceof SphringRunner;
    }

    /**
     * @return EventAnnotation
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param EventAnnotation $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

}
