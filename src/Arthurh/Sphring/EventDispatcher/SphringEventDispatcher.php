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
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class SphringEventDispatcher
 * @package Arthurh\Sphring\EventDispatcher
 */
class SphringEventDispatcher extends EventDispatcher
{
    /**
     * @var SphringEventDispatcher
     */
    private static $_instance = null;

    /**
     * @return SphringEventDispatcher
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new SphringEventDispatcher();
        }

        return self::$_instance;
    }

    public function dispatch($eventName, Event $event = null)
    {
        LoggerSphring::getInstance()->debug(sprintf("Trigger event '%s'", $eventName));
        return parent::dispatch($eventName, $event);
    }

    public function addListener($eventName, $listener, $priority = 0)
    {
        $listenerName = "";
        if (!empty($listener[0]) && is_object($listener[0])) {
            $listenerName = get_class($listener[0]);
        }
        LoggerSphring::getInstance()->debug(sprintf("Add listener '%s' on event '%s'", $listenerName, $eventName));
        parent::addListener($eventName, $listener, $priority);

    }

    public function removeListener($eventName, $listener)
    {
        $listenerName = "";
        if (!empty($listener[0]) && is_object($listener[0])) {
            $listenerName = get_class($listener[0]);
        }
        LoggerSphring::getInstance()->debug(sprintf("Remove listener '%s on event '%s'", $listenerName, $eventName));
        parent::removeListener($eventName, $listener);
    }
} 