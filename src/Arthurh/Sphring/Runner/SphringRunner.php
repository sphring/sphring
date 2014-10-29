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

namespace Arthurh\Sphring\Runner;

use Arthurh\Sphring\EventDispatcher\AnnotationsDispatcher;
use Arthurh\Sphring\Model\Bean\Bean;
use Arthurh\Sphring\Sphring;

/**
 * Class SphringRunner
 * @package Arthurh\Sphring\Runner
 */
abstract class SphringRunner
{
    /**
     * @var SphringRunner
     */
    protected static $_instance;
    /**
     * @var Sphring
     */
    private $sphring;

    /**
     *
     */
    protected function __construct()
    {
        $this->sphring = new Sphring();
        $this->sphring->beforeLoad();
        $this->dispatchAnnotations();
    }

    /**
     *
     */
    private function dispatchAnnotations()
    {
        $bean = new Bean(get_class($this));
        $bean->setObject($this);
        $annotationDispatcher = new AnnotationsDispatcher($bean, get_class($this), $this->sphring->getSphringEventDispatcher());
        $annotationDispatcher->dispatchAnnotations();
    }

    /**
     * @return self
     */
    public final static function getInstance()
    {
        if (null === static::$_instance) {
            static::$_instance = new static();
            static::$_instance->getSphring()->loadContext();
        }

        return static::$_instance;
    }

    /**
     * @return Sphring
     */
    public function getSphring()
    {
        return $this->sphring;
    }

    /**
     * @param $beanId
     * @return object
     */
    public function getBean($beanId)
    {
        return $this->sphring->getBean($beanId);
    }
}
