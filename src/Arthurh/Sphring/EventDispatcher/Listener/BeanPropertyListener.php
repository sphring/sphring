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


use Arthurh\Sphring\Enum\SphringEventEnum;
use Arthurh\Sphring\EventDispatcher\EventBeanProperty;
use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Exception\SphringException;

class BeanPropertyListener
{
    private $mapEventToBeanProperty;
    private static $_instance = null;

    public function register($eventName, $className)
    {
        $eventName = SphringEventEnum::PROPERTY . $eventName;
        $this->mapEventToBeanProperty[$eventName] = $className;
        SphringEventDispatcher::getInstance()->addListener($eventName, array($this, 'onEvent'));
    }

    /**
     * @return mixed
     */
    public function getMapEventToBeanProperty()
    {
        return $this->mapEventToBeanProperty;
    }

    /**
     * @param mixed $mapEventToBeanProperty
     */
    public function setMapEventToBeanProperty($mapEventToBeanProperty)
    {
        $this->mapEventToBeanProperty = $mapEventToBeanProperty;
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new BeanPropertyListener();
        }

        return self::$_instance;
    }

    public function  onEvent(EventBeanProperty $event)
    {
        $propertyName = $this->mapEventToBeanProperty[$event->getName()];
        try {
            $property = new \ReflectionClass($propertyName);
            $propertyClass = $property->newInstance($event->getData());

        } catch (\Exception $e) {
            throw new SphringException("Error when declaring property name '%s', property '%s' doesn't exist", $event->getPropertyKey(), $propertyName, $e);
        }
        $event->setBeanProperty($propertyClass);
    }

} 