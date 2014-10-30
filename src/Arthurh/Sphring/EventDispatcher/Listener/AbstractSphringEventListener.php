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

namespace Arthurh\Sphring\EventDispatcher\Listener;

use Arthurh\Sphring\EventDispatcher\AbstractSphringEvent;
use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Exception\SphringEventListenerException;
use Arthurh\Sphring\Logger\LoggerSphring;

/**
 * The abstract class to implement EventListener for sphring
 * Class AbstractSphringEventListener
 * @package Arthurh\Sphring\EventDispatcher\Listener
 */
abstract class AbstractSphringEventListener
{
    /**
     * Mapping betwwen even name and class name
     * @var array(string => string)
     */
    protected $registers;
    /**
     * @var
     */
    protected $object;
    /**
     * @var SphringEventDispatcher
     */
    protected $sphringEventDispatcher;

    /**
     * Constructor
     */
    public function __construct(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
    }

    /**
     * Register your event
     * @param string $eventName
     * @param $className
     * @param int $priority
     * @param bool $queued
     */
    public function register($eventName, $className, $priority = 0, $queued = false)
    {
        $eventName = $this->getDefaultEventName() . $eventName;
        LoggerSphring::getInstance()->debug(sprintf("Registering event '%s' for class '%s'", $eventName, $className));
        $this->registers[$eventName] = $className;
        $this->sphringEventDispatcher->addListener($eventName, array($this, 'onEvent'), $priority, $queued);
    }

    /**
     * Return the name of the event triggered
     * @return mixed
     */
    abstract public function getDefaultEventName();

    /**
     * Return all listener registered
     * @return array
     */
    public function getRegisters()
    {
        return $this->registers;
    }

    /**
     * Set listener
     * @param array $registers
     */
    public function setRegisters($registers)
    {
        $this->registers = $registers;
    }

    /**
     * Event which will be triggered
     * @param $event
     * @throws \Arthurh\Sphring\Exception\SphringEventListenerException
     */
    public function  onEvent($event)
    {
        if (!($event instanceof AbstractSphringEvent)) {
            throw new SphringEventListenerException("Error when trigger event '%s', event must extends '%s'", $event->getName(), 'AbstractSphringEvent');
        }
        $className = $this->registers[$event->getName()];

        try {
            $class = new \ReflectionClass($className);
            $this->object = $class->newInstance();
            $event->setObject($this->object);
            $event->setSphringEventDispatcher($this->getSphringEventDispatcher());
        } catch (\Exception $e) {
            throw new SphringEventListenerException("Error when trigger event '%s' during class '%s' creation", $event->getName(), $className, $e);
        }

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
    public function setSphringEventDispatcher(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
    }

    /**
     * Return the object passed to this event listener
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Passed an object to this event listener
     * @param mixed $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

}
