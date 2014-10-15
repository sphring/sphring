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


namespace Arthurh\Sphring\EventDispatcher\Listener;


use Arthurh\Sphring\Enum\SphringEventEnum;
use Arthurh\Sphring\EventDispatcher\AbstractSphringEvent;
use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Exception\SphringEventListenerException;
use Symfony\Component\EventDispatcher\Event;

abstract class AbstractSphringEventListener
{
    /**
     * @var AbstractSphringEventListener
     */
    protected static $_instance;
    /**
     * Mapping betwwen even name and class name
     * @var array(string => string)
     */
    protected $registers;
    protected $object;

    /**
     * Constructor
     *
     * @return void
     */
    protected function __construct()
    {
    }

    public static function register($eventName, $className, $priority = 0)
    {
        self::getInstance()->addRegister($eventName, $className, $priority);
    }

    private function addRegister($eventName, $className, $priority = 0)
    {
        $eventName = $this->getDefaultEventName() . $eventName;
        $this->registers[$eventName] = $className;
        SphringEventDispatcher::getInstance()->addListener($eventName, array($this, 'onEvent'), $priority);
    }

    abstract public function getDefaultEventName();

    /**
     * Get instance
     *
     * @return AbstractSphringEventListener
     */
    public final static function getInstance()
    {
        if (null === static::$_instance) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }

    /**
     * @return array
     */
    public function getRegisters()
    {
        return $this->registers;
    }

    /**
     * @param array $registers
     */
    public function setRegisters($registers)
    {
        $this->registers = $registers;
    }

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
        } catch (\Exception $e) {
            throw new SphringEventListenerException("Error when trigger event '%s' during class '%s' creation", $event->getName(), $className, $e);
        }

    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param mixed $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

} 