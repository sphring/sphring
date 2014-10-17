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

class EventBeanProperty extends AbstractSphringEvent
{
    private $data;

    /**
     * @return AbstractBeanProperty
     */
    public function getBeanProperty()
    {
        return $this->getObject();
    }

    /**
     * @param AbstractBeanProperty $beanProperty
     */
    public function setBeanProperty($beanProperty)
    {
        $this->object = $beanProperty;
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


}
