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


use Arthurh\Sphring\Model\Bean;

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

}
