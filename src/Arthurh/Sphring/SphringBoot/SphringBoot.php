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

namespace Arthurh\Sphring\SphringBoot;

use Arthurh\Sphring\ComposerManager\ComposerManager;
use Arthurh\Sphring\EventDispatcher\Listener\SphringGlobalListener;
use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Model\Bean\Bean;
use Arthurh\Sphring\Model\Bean\BeanAbstract;

/**
 *
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
     * @var ComposerManager
     */
    private $composerManager;

    /**
     * @var SphringGlobalListener
     */
    private $sphringGlobalListener;

    /**
     * @var SphringBootAnnotation
     */
    private $sphringBootAnnotation;

    /**
     * @var SphringBootBeanProperty
     */
    private $sphringBootBeanProperty;

    /**
     * @var SphringBootYamlarhEnv
     */
    private $sphringBootYamlarhEnv;

    /**
     * @param SphringEventDispatcher $sphringEventDispatcher
     */
    function __construct(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
        $this->sphringGlobalListener = new SphringGlobalListener($this->sphringEventDispatcher);
        $this->composerManager = new ComposerManager();
        $this->sphringBootAnnotation = new SphringBootAnnotation($this->sphringEventDispatcher);
        $this->sphringBootBeanProperty = new SphringBootBeanProperty($this->sphringEventDispatcher);
        $this->sphringBootYamlarhEnv = new SphringBootYamlarhEnv($this->sphringEventDispatcher->getSphring());
    }

    /**
     *
     */
    public function boot()
    {
        $this->bootPhpConfig();
        $this->bootBeanTypeForFactory();
        $this->sphringBootBeanProperty->bootBeanProperty();
        $this->sphringBootAnnotation->bootAnnotations();
        $this->bootFromComposer();
        $this->sphringBootYamlarhEnv->boot();
    }

    /**
     *
     */
    public function bootPhpConfig()
    {
        error_reporting(ini_get("error_reporting") & ~E_NOTICE);
    }

    /**
     *
     */
    public function bootBeanTypeForFactory()
    {
        $factoryBean = $this->getSphringEventDispatcher()->getSphring()->getFactoryBean();
        $factoryBean->addBeanType('abstract', BeanAbstract::class);
        $factoryBean->addBeanType('normal', Bean::class);
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
     *
     */
    public function bootFromComposer()
    {
        $this->composerManager->setExtender($this->sphringEventDispatcher->getSphring()->getExtender());
        $this->composerManager->setRootProject($this->sphringEventDispatcher->getSphring()->getRootProject());
        $this->composerManager->loadComposer();
    }

    /**
     * @return ComposerManager
     */
    public function getComposerManager()
    {
        return $this->composerManager;
    }

    /**
     * @param ComposerManager $composerManager
     */
    public function setComposerManager(ComposerManager $composerManager)
    {
        $this->composerManager = $composerManager;

    }

    /**
     * @return SphringGlobalListener
     */
    public function getSphringGlobalListener()
    {
        return $this->sphringGlobalListener;
    }

    /**
     * @param SphringGlobalListener $sphringGlobalListener
     */
    public function setSphringGlobalListener($sphringGlobalListener)
    {
        $this->sphringGlobalListener = $sphringGlobalListener;
    }

    /**
     * @return SphringBootAnnotation
     */
    public function getSphringBootAnnotation()
    {
        return $this->sphringBootAnnotation;
    }

    /**
     * @param SphringBootAnnotation $sphringBootAnnotation
     */
    public function setSphringBootAnnotation(SphringBootAnnotation $sphringBootAnnotation)
    {
        $this->sphringBootAnnotation = $sphringBootAnnotation;
    }

    /**
     * @return SphringBootBeanProperty
     */
    public function getSphringBootBeanProperty()
    {
        return $this->sphringBootBeanProperty;
    }

    /**
     * @param SphringBootBeanProperty $sphringBootBeanProperty
     */
    public function setSphringBootBeanProperty(SphringBootBeanProperty $sphringBootBeanProperty)
    {
        $this->sphringBootBeanProperty = $sphringBootBeanProperty;
    }

    /**
     * @return SphringBootYamlarhEnv
     */
    public function getSphringBootYamlarhEnv()
    {
        return $this->sphringBootYamlarhEnv;
    }

    /**
     * @param SphringBootYamlarhEnv $sphringBootYamlarhEnv
     */
    public function setSphringBootYamlarhEnv(SphringBootYamlarhEnv $sphringBootYamlarhEnv)
    {
        $this->sphringBootYamlarhEnv = $sphringBootYamlarhEnv;
    }


}
