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


use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Model\Bean;
use Arthurh\Sphring\Runner\SphringRunner;

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

    abstract public function run();

    protected function isSetter()
    {
        if (!($this->reflector instanceof \ReflectionMethod)) {
            return false;
        }
        $name = $this->reflector->getName();
        $parse = substr($name, 0, 3);
        return $parse == 'set';
    }

    protected function isGetter()
    {
        if (!($this->reflector instanceof \ReflectionMethod)) {
            return false;
        }
        $name = $this->reflector->getName();
        $parse = substr($name, 0, 3);
        return $parse == 'get';
    }

    protected function isInSphringRunner()
    {

        return $this->bean->getObject() instanceof SphringRunner;
    }
}
