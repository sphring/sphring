<?php
/**
 * Copyright (C) 2014 Orange
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 15/10/2014
 */


namespace Arthurh\Sphring\Extender;

use Arthurh\Sphring\Exception\ExtenderException;


/**
 * Class Node
 * @package Arthurh\Sphring\Extender
 */
class Node
{
    /**
     * @var string
     */
    private $eventName;
    /**
     * @var string
     */
    private $className;
    /**
     * @var int
     */
    private $priority = 0;

    /**
     * @param string $className
     * @param string $eventName
     */
    function __construct($eventName, $className, $priority = null)
    {
        if (empty($priority)) {
            $this->priority = 0;
        } else {
            $this->priority = (int)$priority;
        }

        $this->setClassName($className);
        $this->eventName = $eventName;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     * @throws \Arthurh\Sphring\Exception\ExtenderException
     */
    public function setClassName($className)
    {
        if (!class_exists($className)) {
            throw new ExtenderException("class '%s' doesn't exist and can't be registered.");
        }
        $this->className = $className;
    }

    /**
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @param string $eventName
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = (int)$priority;
    }

}
