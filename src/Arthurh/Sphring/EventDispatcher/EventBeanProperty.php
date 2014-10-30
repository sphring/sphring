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

use Arthurh\Sphring\Model\BeanProperty\AbstractBeanProperty;

/**
 * Event when bean property are read
 * Class EventBeanProperty
 * @package Arthurh\Sphring\EventDispatcher
 */
class EventBeanProperty extends AbstractSphringEvent
{
    /**
     * @var
     */
    private $data;

    /**
     * Get the bean property
     * @return AbstractBeanProperty
     */
    public function getBeanProperty()
    {
        return $this->getObject();
    }

    /**
     * Set the bean property
     * @param AbstractBeanProperty $beanProperty
     */
    public function setBeanProperty($beanProperty)
    {
        $this->object = $beanProperty;
    }

    /**
     * Get data passed to this event
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set data passed to this event
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

}
