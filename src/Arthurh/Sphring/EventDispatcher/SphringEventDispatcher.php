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

use Arthurh\Sphring\Logger\LoggerSphring;
use Arthurh\Sphring\Sphring;
use Arthurh\Sphring\SphringBoot\SphringBoot;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * SphringEventDispatcher extends EventDispatcher
 * Listeners are registered on the manager and events are dispatched through the
 * manager.
 * You can also put in queue event which will be triggered when load context is finished
 * Class SphringEventDispatcher
 * @package Arthurh\Sphring\EventDispatcher
 */
class SphringEventDispatcher extends EventDispatcher
{

    /**
     * @var Sphring
     */
    private $sphring;
    /**
     * @var SphringBoot
     */
    private $sphringBoot;

    /**
     * @var bool
     */
    private $isLoaded = false;

    /**
     * @var Event[]
     */
    private $queue = array();

    /**
     * Constructor
     * @param Sphring $sphring
     */
    public function __construct(Sphring $sphring)
    {
        $this->sphring = $sphring;
        $this->sphringBoot = new SphringBoot($this);
    }

    /**
     * @see EventDispatcher::dispatch
     * @param string $eventName
     * @param Event $event
     * @return null|Event
     */
    public function dispatch($eventName, Event $event = null)
    {
        if (isset($this->queue[$eventName])) {
            $this->queue[$eventName][] = $event;
            return null;
        }
        LoggerSphring::getInstance()->debug(sprintf("Trigger event '%s'", $eventName));
        return parent::dispatch($eventName, $event);
    }

    /**
     * Dispatch events from queue
     * @return mixed
     */
    public function dispatchQueue()
    {

        $eventsFromQueue = [];
        foreach ($this->queue as $eventName => $events) {
            LoggerSphring::getInstance()->debug(sprintf("Trigger event '%s'", $eventName));
            $eventsFromQueue[$eventName] = $this->dispatchQueueEvents($eventName, $events);
        }
        $this->queue = array();
        return $eventsFromQueue[$eventName];
    }

    /**
     * Dispatch events from queue
     * @param integer $eventName
     * @param Event[] $events
     * @return Event[]
     */
    private function dispatchQueueEvents($eventName, $events)
    {
        $eventsFromQueue = [];
        foreach ($events as $event) {
            $eventsFromQueue[] = parent::dispatch($eventName, $event);
        }
        return $eventsFromQueue;
    }

    /**
     * Initialize SphringEventDispatcher by register event listener from SphringBoot
     */
    public function load()
    {
        if ($this->isLoaded) {
            return;
        }
        $this->sphringBoot->boot();
        $this->isLoaded = true;
    }

    /**
     * @see EventDispatcher::addListener
     * @param string $eventName
     * @param callable $listener
     * @param int $priority
     * @param bool $queued
     */
    public function addListener($eventName, $listener, $priority = 0, $queued = false)
    {
        if ($queued && empty($this->queue[$eventName])) {
            $this->queue[$eventName] = [];
        }
        $listenerName = "";
        if (!empty($listener[0]) && is_object($listener[0])) {
            $listenerName = get_class($listener[0]);
        }
        LoggerSphring::getInstance()->debug(sprintf("Add listener '%s' on event '%s'", $listenerName, $eventName));
        parent::addListener($eventName, $listener, $priority);

    }

    /**
     * @see EventDispatcher::removeListener
     * @param string $eventName
     * @param callable $listener
     */
    public function removeListener($eventName, $listener)
    {
        $listenerName = "";
        if (!empty($listener[0]) && is_object($listener[0])) {
            $listenerName = get_class($listener[0]);
        }
        LoggerSphring::getInstance()->debug(sprintf("Remove listener '%s on event '%s'", $listenerName, $eventName));
        parent::removeListener($eventName, $listener);
    }

    /**
     * Return Sphring object
     * @return Sphring
     */
    public function getSphring()
    {
        return $this->sphring;
    }

    /**
     * Set the Sphring object
     * @param Sphring $sphring
     */
    public function setSphring(Sphring $sphring)
    {
        $this->sphring = $sphring;
    }

    /**
     * Return SphringBoot object
     * @return SphringBoot
     */
    public function getSphringBoot()
    {
        return $this->sphringBoot;
    }

    /**
     * Set the SphringBoot object
     * @param SphringBoot $sphringBoot
     */
    public function setSphringBoot(SphringBoot $sphringBoot)
    {
        $this->sphringBoot = $sphringBoot;

    }

    /**
     * Return true if SphringEventDispatcher has finished to load
     * @return boolean
     */
    public function getLoaded()
    {
        return $this->isLoaded;
    }

    /**
     * Set if SphringEventDispatcher has finished to load
     * @param boolean $isLoaded
     */
    public function setLoaded($isLoaded)
    {
        $this->isLoaded = $isLoaded;
    }

    /**
     * Return the queue
     * @return Event[]
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Set the queue
     * @param Event[] $queue
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;
    }

}
