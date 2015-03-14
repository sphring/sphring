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

namespace Arthurh\Sphring\EventDispatcher;

use Arthurh\Sphring\Model\Bean\Bean;

/**
 * Event for annotation
 * Class EventAnnotation
 * @package Arthurh\Sphring\EventDispatcher
 */
class EventAnnotation extends AbstractSphringEvent
{
    private $data;
    /**
     * @var \Reflector
     */
    private $reflector;
    /**
     * @var Bean
     */
    private $bean;

    /**
     * @var mixed[]
     */
    private $methodArgs;

    /**
     * Return data passed to this event
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set data for this event
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Get the reflector class
     * @return \Reflector
     */
    public function getReflector()
    {
        return $this->reflector;
    }

    /**
     * Set the reflector class
     * @param \Reflector $reflector
     */
    public function setReflector($reflector)
    {
        $this->reflector = $reflector;
    }

    /**
     * Return the bean passed to this event
     * @return Bean
     */
    public function getBean()
    {
        return $this->bean;
    }

    /**
     * Set the bean passed to this event
     * @param Bean $bean
     */
    public function setBean($bean)
    {
        $this->bean = $bean;
    }

    /**
     * @return mixed[]
     */
    public function getMethodArgs()
    {
        return $this->methodArgs;
    }

    /**
     * @param mixed[] $methodArgs
     */
    public function setMethodArgs($methodArgs)
    {
        if (!is_array($methodArgs)) {
            $methodArgs = [$methodArgs];
        }
        $this->methodArgs = $methodArgs;
    }

}
