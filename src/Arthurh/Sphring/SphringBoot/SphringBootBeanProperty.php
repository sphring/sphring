<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 14/03/2015
 */

namespace Arthurh\Sphring\SphringBoot;


use Arthurh\Sphring\EventDispatcher\Listener\BeanPropertyListener;
use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Model\BeanProperty\BeanPropertyFile;
use Arthurh\Sphring\Model\BeanProperty\BeanPropertyFolder;
use Arthurh\Sphring\Model\BeanProperty\BeanPropertyIniFile;
use Arthurh\Sphring\Model\BeanProperty\BeanPropertyJson;
use Arthurh\Sphring\Model\BeanProperty\BeanPropertyRef;
use Arthurh\Sphring\Model\BeanProperty\BeanPropertyStream;
use Arthurh\Sphring\Model\BeanProperty\BeanPropertyValue;
use Arthurh\Sphring\Model\BeanProperty\BeanPropertyXml;
use Arthurh\Sphring\Model\BeanProperty\BeanPropertyYml;

class SphringBootBeanProperty
{
    /**
     * @var BeanPropertyListener
     */
    private $beanPropertyListener;
    /**
     * @var SphringEventDispatcher
     */
    private $sphringEventDispatcher;

    function __construct(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
        $this->beanPropertyListener = new BeanPropertyListener($this->sphringEventDispatcher);
    }

    /**
     *
     */
    public function bootBeanProperty()
    {
        $beanProperty = $this->beanPropertyListener;
        $beanProperty->register('iniFile', BeanPropertyIniFile::class);
        $beanProperty->register('file', BeanPropertyFile::class);
        $beanProperty->register('folder', BeanPropertyFolder::class);
        $beanProperty->register('ref', BeanPropertyRef::class);
        $beanProperty->register('stream', BeanPropertyStream::class);
        $beanProperty->register('value', BeanPropertyValue::class);
        $beanProperty->register('yml', BeanPropertyYml::class);
        $beanProperty->register('json', BeanPropertyJson::class);
        $beanProperty->register('xml', BeanPropertyXml::class);
    }

    /**
     * @return BeanPropertyListener
     */
    public function getBeanPropertyListener()
    {
        return $this->beanPropertyListener;
    }

    /**
     * @param BeanPropertyListener $beanPropertyListener
     */
    public function setBeanPropertyListener(BeanPropertyListener $beanPropertyListener)
    {
        $this->beanPropertyListener = $beanPropertyListener;
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
}
