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


use Arthurh\Sphring\EventDispatcher\AbstractSphringEvent;
use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Exception\SphringEventListenerException;
use Arthurh\Sphring\Logger\LoggerSphring;

abstract class AbstractSphringEventListener
{
    /**
     * Mapping betwwen even name and class name
     * @var array(string => string)
     */
    protected $registers;
    protected $object;
    /**
     * @var SphringEventDispatcher
     */
    protected $sphringEventDispatcher;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
    }


    /**
     * @param string $eventName
     */
    public function register($eventName, $className, $priority = 0)
    {
        $eventName = $this->getDefaultEventName() . $eventName;
        LoggerSphring::getInstance()->debug(sprintf("Registering event '%s' for class '%s'", $eventName, $className));
        $this->registers[$eventName] = $className;
        $this->sphringEventDispatcher->addListener($eventName, array($this, 'onEvent'), $priority);
    }

    abstract public function getDefaultEventName();


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
            $event->setSphringEventDispatcher($this->getSphringEventDispatcher());
        } catch (\Exception $e) {
            throw new SphringEventListenerException("Error when trigger event '%s' during class '%s' creation", $event->getName(), $className, $e);
        }

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
    public function setSphringEventDispatcher(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
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
