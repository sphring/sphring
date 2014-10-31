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

use Arthurh\Sphring\ComposerManager\ComposerManager;
use Arthurh\Sphring\EventDispatcher\Listener\AnnotationClassListener;
use Arthurh\Sphring\EventDispatcher\Listener\AnnotationMethodListener;
use Arthurh\Sphring\EventDispatcher\Listener\BeanPropertyListener;
use Arthurh\Sphring\EventDispatcher\Listener\SphringGlobalListener;
use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Model\Annotation\AfterLoadMethodOnSphringEventAnnotation;
use Arthurh\Sphring\Model\Annotation\AutoWireAnnotation;
use Arthurh\Sphring\Model\Annotation\BeforeLoadMethodOnSphringEventAnnotation;
use Arthurh\Sphring\Model\Annotation\BeforeStartMethodOnSphringEventAnnotation;
use Arthurh\Sphring\Model\Annotation\LoadContextAnnotation;
use Arthurh\Sphring\Model\Annotation\RequiredAnnotation;
use Arthurh\Sphring\Model\Annotation\RootProjectAnnotation;
use Arthurh\Sphring\Model\Bean\Bean;
use Arthurh\Sphring\Model\Bean\BeanAbstract;
use Arthurh\Sphring\Model\BeanProperty\BeanPropertyIniFile;
use Arthurh\Sphring\Model\BeanProperty\BeanPropertyRef;
use Arthurh\Sphring\Model\BeanProperty\BeanPropertyStream;
use Arthurh\Sphring\Model\BeanProperty\BeanPropertyValue;
use Arthurh\Sphring\Model\BeanProperty\BeanPropertyYml;

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

    private $beanPropertyListener;
    /**
     * @var AnnotationClassListener
     */
    private $annotationClassListener;
    /**
     * @var AnnotationMethodListener
     */
    private $annotationMethodListener;

    /**
     * @var ComposerManager
     */
    private $composerManager;

    /**
     * @var SphringGlobalListener
     */
    private $sphringGlobalListener;

    /**
     * @param SphringEventDispatcher $sphringEventDispatcher
     */
    function __construct(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
        $this->beanPropertyListener = new BeanPropertyListener($this->sphringEventDispatcher);
        $this->annotationMethodListener = new AnnotationMethodListener($this->sphringEventDispatcher);
        $this->annotationClassListener = new AnnotationClassListener($this->sphringEventDispatcher);
        $this->sphringGlobalListener = new SphringGlobalListener($this->sphringEventDispatcher);
        $this->composerManager = new ComposerManager();
    }

    /**
     *
     */
    public function boot()
    {
        $this->bootBeanTypeForFactory();
        $this->bootBeanProperty();
        $this->bootAnnotations();
        $this->bootFromComposer();
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
    public function bootBeanProperty()
    {
        $beanProperty = $this->beanPropertyListener;
        $beanProperty->register('iniFile', BeanPropertyIniFile::class);
        $beanProperty->register('ref', BeanPropertyRef::class);
        $beanProperty->register('stream', BeanPropertyStream::class);
        $beanProperty->register('value', BeanPropertyValue::class);
        $beanProperty->register('yml', BeanPropertyYml::class);

    }

    /**
     *
     */
    public function bootAnnotations()
    {
        $this->bootAnnotationClass();
        $this->bootAnnotationMethod();
    }

    /**
     *
     */
    public function bootAnnotationClass()
    {
        $this->annotationClassListener->register(LoadContextAnnotation::getAnnotationName(), LoadContextAnnotation::class);
        $this->annotationClassListener->register(RootProjectAnnotation::getAnnotationName(), RootProjectAnnotation::class);
    }

    /**
     *
     */
    public function bootAnnotationMethod()
    {
        $this->annotationMethodListener->register(RequiredAnnotation::getAnnotationName(), RequiredAnnotation::class);
        $this->annotationMethodListener->register(AutoWireAnnotation::getAnnotationName(), AutoWireAnnotation::class, 0, true);
        $this->annotationMethodListener->register(AfterLoadMethodOnSphringEventAnnotation::getAnnotationName(), AfterLoadMethodOnSphringEventAnnotation::class);
        $this->annotationMethodListener->register(BeforeLoadMethodOnSphringEventAnnotation::getAnnotationName(), BeforeLoadMethodOnSphringEventAnnotation::class);
        $this->annotationMethodListener->register(BeforeStartMethodOnSphringEventAnnotation::getAnnotationName(), BeforeStartMethodOnSphringEventAnnotation::class);

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
     * @return BeanPropertyListener
     */
    public function getBeanPropertyListener()
    {
        return $this->beanPropertyListener;
    }

    /**
     * @param BeanPropertyListener $beanProperty
     */
    public function setBeanPropertyListener(BeanPropertyListener $beanProperty)
    {
        $this->beanPropertyListener = $beanProperty;
        $this->beanPropertyListener->setSphringEventDispatcher($this->getSphringEventDispatcher());
    }

    /**
     * @return AnnotationClassListener
     */
    public function getAnnotationClassListener()
    {
        return $this->annotationClassListener;
    }

    /**
     * @param AnnotationClassListener $annotationClassListener
     */
    public function setAnnotationClassListener($annotationClassListener)
    {
        $this->annotationClassListener = $annotationClassListener;
    }

    /**
     * @return AnnotationMethodListener
     */
    public function getAnnotationMethodListener()
    {
        return $this->annotationMethodListener;
    }

    /**
     * @param AnnotationMethodListener $annotationMethodListener
     */
    public function setAnnotationMethodListener($annotationMethodListener)
    {
        $this->annotationMethodListener = $annotationMethodListener;
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

}
