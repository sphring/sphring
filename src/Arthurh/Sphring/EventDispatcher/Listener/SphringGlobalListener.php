<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 31/10/2014
 */


namespace Arthurh\Sphring\EventDispatcher\Listener;


use Arthurh\Sphring\Enum\SphringEventEnum;
use Arthurh\Sphring\EventDispatcher\EventSphring;
use Arthurh\Sphring\Exception\SphringEventListenerException;
use Arthurh\Sphring\Model\SphringGlobal;

/**
 * EventListener which Triggered event for global sphring event
 * Class SphringGlobalListener
 * @package Arthurh\Sphring\EventDispatcher\Listener
 */
class SphringGlobalListener extends AbstractSphringEventListener
{
    /**
     * Register your event
     * @param string $eventName
     * @param $className
     * @param int $priority
     * @param bool $queued
     */
    public function register($eventName, $className, $priority = 0, $queued = false)
    {
        if (defined(SphringEventEnum::class . "::" . $eventName)) {
            $eventName = SphringEventEnum::$eventName();
        }
        parent::register($eventName, $className, $priority, $queued);
    }

    /**
     * Event which will be triggered
     * @param $event
     * @throws \Arthurh\Sphring\Exception\SphringEventListenerException
     */
    public function  onEvent($event)
    {
        if (!($event instanceof EventSphring)) {
            throw new SphringEventListenerException("Event must be an '%s'", EventSphring::class);
        }
        parent::onEvent($event);
        if (!($this->object instanceof SphringGlobal)) {
            throw new SphringEventListenerException("Class '%s' must extends '%s'", get_class($this->object), SphringGlobal::class);
        }
        $this->object->setSphring($this->sphringEventDispatcher->getSphring());
        $this->object->run();
    }

    /**
     * Return the name of the event triggered
     * @return string
     */
    public function getDefaultEventName()
    {
        return "";
    }
}
