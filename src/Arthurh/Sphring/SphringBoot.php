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

namespace Arthurh\Sphring;


use Arthurh\Sphring\EventDispatcher\Listener\BeanPropertyListener;
use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;

/**
 * Class SphringBoot
 * @package Arthurh\Sphring
 */
class SphringBoot
{
    /**
     * @var SphringEventDispatcher
     */
    private $sphringEventDispatcher;

    /**
     * @var BeanPropertyListener
     */
    private $beanProperty;

    function __construct(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
        $this->setBeanProperty(new BeanPropertyListener($this->sphringEventDispatcher));
    }

    /**
     *
     */
    public function boot()
    {
        $this->bootBeanProperty();
    }

    /**
     *
     */
    public function bootBeanProperty()
    {
        $beanProperty = $this->beanProperty;
        $beanProperty->register('iniFile', "Arthurh\\Sphring\\Model\\BeanProperty\\BeanPropertyIniFile");
        $beanProperty->register('ref', "Arthurh\\Sphring\\Model\\BeanProperty\\BeanPropertyRef");
        $beanProperty->register('stream', "Arthurh\\Sphring\\Model\\BeanProperty\\BeanPropertyStream");
        $beanProperty->register('value', "Arthurh\\Sphring\\Model\\BeanProperty\\BeanPropertyValue");
        $beanProperty->register('yml', "Arthurh\\Sphring\\Model\\BeanProperty\\BeanPropertyYml");
    }

    /**
     * @return SphringEventDispatcher
     */
    public function getSphringEventDispatcher()
    {
        return $this->sphringEventDispatcher;
    }

    /**
     * @param SphringEventDispatcher $sphringEventDispatcher
     */
    public function setSphringEventDispatcher(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
    }

    /**
     * @return BeanPropertyListener
     */
    public function getBeanProperty()
    {
        return $this->beanProperty;
    }

    /**
     * @param BeanPropertyListener $beanProperty
     */
    public function setBeanProperty(BeanPropertyListener $beanProperty)
    {
        $this->beanProperty = $beanProperty;
        $this->beanProperty->setSphringEventDispatcher($this->getSphringEventDispatcher());
    }


} 