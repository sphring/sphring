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
use Symfony\Component\EventDispatcher\Event;

class EventBeanProperty extends Event
{
    private $data;
    private $name;
    private $propertyKey;
    /**
     * @var AbstractBeanProperty
     */
    private $beanProperty;

    /**
     * @return AbstractBeanProperty
     */
    public function getBeanProperty()
    {
        return $this->beanProperty;
    }

    /**
     * @param AbstractBeanProperty $beanProperty
     */
    public function setBeanProperty($beanProperty)
    {
        $this->beanProperty = $beanProperty;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPropertyKey()
    {
        return $this->propertyKey;
    }

    /**
     * @param mixed $propertyKey
     */
    public function setPropertyKey($propertyKey)
    {
        $this->propertyKey = $propertyKey;
    }


} 