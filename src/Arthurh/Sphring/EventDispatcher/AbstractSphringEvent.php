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

use Symfony\Component\EventDispatcher\Event;

/**
 * Abstract sphring event
 * Class AbstractSphringEvent
 * @package Arthurh\Sphring\EventDispatcher
 */
abstract class AbstractSphringEvent extends Event
{
    /**
     * @var SphringEventDispatcher
     */
    protected $sphringEventDispatcher;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var object
     */
    protected $object;

    /**
     * Return the name of this event
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name of this event
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Return the object passed to this event
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Passed an object to this event
     * @param object $object
     */
    public function setObject($object)
    {
        $this->object = $object;
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
    public function setSphringEventDispatcher($sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
    }

}
