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


use Arthurh\Sphring\EventDispatcher\Listener\AnnotationClassListener;
use Arthurh\Sphring\EventDispatcher\Listener\AnnotationMethodListener;
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

    private $beanPropertyListener;
    /**
     * @var AnnotationClassListener
     */
    private $annotationClassListener;
    /**
     * @var AnnotationMethodListener
     */
    private $annotationMethodListener;

    function __construct(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
        $this->beanPropertyListener = new BeanPropertyListener($this->sphringEventDispatcher);
        $this->annotationMethodListener = new AnnotationMethodListener($this->sphringEventDispatcher);
        $this->annotationClassListener = new AnnotationClassListener($this->sphringEventDispatcher);

    }

    /**
     *
     */
    public function boot()
    {
        $this->bootBeanProperty();
        $this->bootAnnotations();
    }

    /**
     *
     */
    public function bootBeanProperty()
    {
        $beanProperty = $this->beanPropertyListener;
        $beanProperty->register('iniFile', "Arthurh\\Sphring\\Model\\BeanProperty\\BeanPropertyIniFile");
        $beanProperty->register('ref', "Arthurh\\Sphring\\Model\\BeanProperty\\BeanPropertyRef");
        $beanProperty->register('stream', "Arthurh\\Sphring\\Model\\BeanProperty\\BeanPropertyStream");
        $beanProperty->register('value', "Arthurh\\Sphring\\Model\\BeanProperty\\BeanPropertyValue");
        $beanProperty->register('yml', "Arthurh\\Sphring\\Model\\BeanProperty\\BeanPropertyYml");
    }

    public function bootAnnotations()
    {
        $this->bootAnnotationClass();
        $this->bootAnnotationMethod();
    }

    public function bootAnnotationClass()
    {
        $this->annotationClassListener->register('loadcontext', "Arthurh\\Sphring\\Model\\Annotation\\LoadContextAnnotation");
    }

    public function bootAnnotationMethod()
    {
        $this->annotationMethodListener->register('required', "Arthurh\\Sphring\\Model\\Annotation\\RequiredAnnotation");
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


} 