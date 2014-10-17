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
use Arthurh\Sphring\EventDispatcher\EventAnnotation;
use Arthurh\Sphring\Exception\SphringEventListenerException;
use Arthurh\Sphring\Model\Annotation\AbstractAnnotation;

class AnnotationClassListener extends AbstractSphringEventListener
{
    public function  onEvent($event)
    {
        if (!($event instanceof EventAnnotation)) {
            throw new SphringEventListenerException("Event must be an EventAnnotation");
        }
        parent::onEvent($event);
        if (!($this->object instanceof AbstractAnnotation)) {
            throw new SphringEventListenerException("Class '%s' must extends '%s'", get_class($this->object), AbstractAnnotation::class);
        }
        $this->object->setBean($event->getBean());
        $this->object->setData($event->getData());
        $this->object->setSphringEventDispatcher($event->getSphringEventDispatcher());
        $this->object->setReflector($event->getReflector());
        $this->object->run();

    }

    public function getDefaultEventName()
    {
        return SphringEventEnum::ANNOTATION_CLASS;
    }
}