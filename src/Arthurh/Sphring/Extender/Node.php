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

namespace Arthurh\Sphring\Extender;

use Arthurh\Sphring\Exception\ExtenderException;

/**
 * A model class to extend sphring.
 * It has event name, a class name and priority for a particular event
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
     * Constructor
     * @param string $eventName
     * @param string $className
     * @param null $priority
     */
    public function __construct($eventName, $className, $priority = null)
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
     * Get the class name of the extension
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Set the class name of the extension
     * @param string $className
     * @throws \Arthurh\Sphring\Exception\ExtenderException
     */
    public function setClassName($className)
    {
        if (!class_exists($className)) {
            throw new ExtenderException("class '%s' doesn't exist and can't be registered.", $className);
        }
        $this->className = $className;
    }

    /**
     * Get the event name of the extension
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * Set the event name of the extension
     * @param string $eventName
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
    }

    /**
     * Get the priority of the extension
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set the priority of the extension
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = (int)$priority;
    }

}
