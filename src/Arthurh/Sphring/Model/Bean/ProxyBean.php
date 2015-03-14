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


use Arthurh\Sphring\EventDispatcher\AnnotationsDispatcher;

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
        $annotationDispatcher = new AnnotationsDispatcher($this->bean, $this->bean->getClass(), $this->bean->getSphringEventDispatcher());

        $annotationDispatcher->setMethodArgs($arguments);
        $toReturn = $annotationDispatcher->dispatchAnnotationMethodCallBefore($name);
        if ($toReturn === null) {
            $toReturn = call_user_func_array(array($this->bean->getObject(), $name), $annotationDispatcher->getMethodArgs());

        }
        $toReturnAfter = $annotationDispatcher->dispatchAnnotationMethodCallAfter($name);
        if ($toReturnAfter !== null) {
            return $toReturnAfter;
        }
        return $toReturn;
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
