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
use Arthurh\Sphring\Exception\SphringEventListenerException;
use Arthurh\Sphring\Model\BeanProperty\AbstractBeanProperty;

class BeanPropertyListener extends AbstractSphringEventListener
{

    /**
     * @param EventBeanProperty $event
     */
    public function  onEvent($event)
    {
        parent::onEvent($event);
        if (!($this->object instanceof AbstractBeanProperty)) {
            throw new SphringEventListenerException("Class '%s' must extends '%s'", get_class($this->object), AbstractBeanProperty::class);
        }

        $this->object->setSphring($this->sphringEventDispatcher->getSphring());
        $this->object->setData($event->getData());

    }

    public function getDefaultEventName()
    {
        return SphringEventEnum::PROPERTY_INJECTION;
    }
}
