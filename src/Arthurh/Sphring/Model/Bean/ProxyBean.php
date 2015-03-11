<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 11/03/2015
 */

namespace Arthurh\Sphring\Model\Bean;


class ProxyBean
{
    /**
     * @var AbstractBean
     */
    private $bean;

    public function __construct(AbstractBean $bean)
    {
        $this->bean = $bean;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array(array($this->bean->getObject(), $name), $arguments);
    }

    public function __get($name)
    {
        $object = $this->bean->getObject();
        return $object->$name;
    }

    public function __set($name, $value)
    {
        $object = $this->bean->getObject();
        $object->$name = $value;
    }

    public function __getBean()
    {
        return $this->bean;
    }
}
