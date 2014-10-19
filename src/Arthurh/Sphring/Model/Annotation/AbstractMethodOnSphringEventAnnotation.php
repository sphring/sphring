<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 18/10/2014
 */

namespace Arthurh\Sphring\Model\Annotation;

use Arthurh\Sphring\Exception\SphringAnnotationException;
use Arthurh\Sphring\Runner\SphringRunner;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class AbstractMethodOnSphringEventAnnotation
 * @package Arthurh\Sphring\Model\Annotation
 */
abstract class AbstractMethodOnSphringEventAnnotation extends AbstractAnnotation
{
    /**
     * @var string
     */
    protected $methodName;

    /**
     * @throws \Arthurh\Sphring\Exception\SphringAnnotationException
     */
    public function run()
    {
        if (!$this->isInSphringRunner()) {
            throw new SphringAnnotationException("Error in bean '%s' in class annotation: Annotation '%s' required to be set on '%s' class.",
                $this->getBean()->getId(), get_class($this), SphringRunner::class);
        }
        if (!$this->isMethod()) {
            throw new SphringAnnotationException("This annotation can only be run on method");
        }
        $this->methodName = $this->reflector->name;
        if (!$this->reflector->isPublic()) {
            throw new SphringAnnotationException("Annotation '%s': method '%s' must be public in class '%s' .",
                get_class($this), $this->methodName, $this->reflector->class);
        }
        $this->getSphringEventDispatcher()->addListener($this->getEventSphring(), array($this, 'onEvent'));
    }

    /**
     * @param Event $event
     */
    function onEvent(Event $event)
    {
        $methodName = $this->methodName;
        $this->getBean()->getObject()->$methodName($event);
    }

    /**
     * @return string
     */
    abstract function getEventSphring();
}
