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


use Arthurh\Sphring\EventDispatcher\Listener\AnnotationClassInstantiateListener;
use Arthurh\Sphring\EventDispatcher\Listener\AnnotationClassListener;
use Arthurh\Sphring\EventDispatcher\Listener\AnnotationMethodCallAfterListener;
use Arthurh\Sphring\EventDispatcher\Listener\AnnotationMethodCallBeforeListener;
use Arthurh\Sphring\EventDispatcher\Listener\AnnotationMethodListener;
use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Model\Annotation\AfterLoadMethodOnSphringEventAnnotation;
use Arthurh\Sphring\Model\Annotation\AopAnnotation\AfterCallAnnotation;
use Arthurh\Sphring\Model\Annotation\AopAnnotation\BeforeCallAnnotation;
use Arthurh\Sphring\Model\Annotation\AutoWireAnnotation;
use Arthurh\Sphring\Model\Annotation\BeforeLoadMethodOnSphringEventAnnotation;
use Arthurh\Sphring\Model\Annotation\BeforeStartMethodOnSphringEventAnnotation;
use Arthurh\Sphring\Model\Annotation\LoadContextAnnotation;
use Arthurh\Sphring\Model\Annotation\MethodInitAnnotation;
use Arthurh\Sphring\Model\Annotation\RequiredAnnotation;
use Arthurh\Sphring\Model\Annotation\RootProjectAnnotation;

class SphringBootAnnotation
{
    /**
     * @var SphringEventDispatcher
     */
    private $sphringEventDispatcher;
    /**
     * @var AnnotationClassListener
     */
    private $annotationClassListener;
    /**
     * @var AnnotationMethodListener
     */
    private $annotationMethodListener;
    /**
     * @var AnnotationClassInstantiateListener
     */
    private $annotationClassInstantiateListener;

    /**
     * @var AnnotationMethodCallAfterListener
     */
    private $annotationMethodCallAfterListener;

    /**
     * @var AnnotationMethodCallBeforeListener
     */
    private $annotationMethodCallBeforeListener;

    function __construct(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
        $this->annotationMethodListener = new AnnotationMethodListener($this->sphringEventDispatcher);
        $this->annotationClassListener = new AnnotationClassListener($this->sphringEventDispatcher);
        $this->annotationClassInstantiateListener = new AnnotationClassInstantiateListener($this->sphringEventDispatcher);
        $this->annotationMethodCallAfterListener = new AnnotationMethodCallAfterListener($this->sphringEventDispatcher);
        $this->annotationMethodCallBeforeListener = new AnnotationMethodCallBeforeListener($this->sphringEventDispatcher);
    }

    /**
     *
     */
    public function bootAnnotations()
    {
        $this->bootAnnotationClass();
        $this->bootAnnotationMethod();
        $this->bootAnnotationMethodCallAfter();
        $this->bootAnnotationMethodCallBefore();
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
        $this->annotationMethodListener->register(MethodInitAnnotation::getAnnotationName(), MethodInitAnnotation::class);
        $this->annotationMethodListener->register(AutoWireAnnotation::getAnnotationName(), AutoWireAnnotation::class, 0, true);
        $this->annotationMethodListener->register(AfterLoadMethodOnSphringEventAnnotation::getAnnotationName(), AfterLoadMethodOnSphringEventAnnotation::class);
        $this->annotationMethodListener->register(BeforeLoadMethodOnSphringEventAnnotation::getAnnotationName(), BeforeLoadMethodOnSphringEventAnnotation::class);
        $this->annotationMethodListener->register(BeforeStartMethodOnSphringEventAnnotation::getAnnotationName(), BeforeStartMethodOnSphringEventAnnotation::class);

    }

    public function bootAnnotationMethodCallAfter()
    {
        $this->annotationMethodCallAfterListener->register(AfterCallAnnotation::getAnnotationName(), AfterCallAnnotation::class);
    }

    public function bootAnnotationMethodCallBefore()
    {
        $this->annotationMethodCallBeforeListener->register(BeforeCallAnnotation::getAnnotationName(), BeforeCallAnnotation::class);
    }

    /**
     * @return AnnotationClassInstantiateListener
     */
    public function getAnnotationClassInstantiateListener()
    {
        return $this->annotationClassInstantiateListener;
    }

    /**
     * @param AnnotationClassInstantiateListener $annotationClassInstantiateListener
     */
    public function setAnnotationClassInstantiateListener($annotationClassInstantiateListener)
    {
        $this->annotationClassInstantiateListener = $annotationClassInstantiateListener;
    }

    /**
     * @return AnnotationMethodCallAfterListener
     */
    public function getAnnotationMethodCallAfterListener()
    {
        return $this->annotationMethodCallAfterListener;
    }

    /**
     * @param AnnotationMethodCallAfterListener $annotationMethodCallAfterListener
     */
    public function setAnnotationMethodCallAfterListener(AnnotationMethodCallAfterListener $annotationMethodCallAfterListener)
    {
        $this->annotationMethodCallAfterListener = $annotationMethodCallAfterListener;
    }

    /**
     * @return AnnotationMethodCallBeforeListener
     */
    public function getAnnotationMethodCallBeforeListener()
    {
        return $this->annotationMethodCallBeforeListener;
    }

    /**
     * @param AnnotationMethodCallBeforeListener $annotationMethodCallBeforeListener
     */
    public function setAnnotationMethodCallBeforeListener(AnnotationMethodCallBeforeListener $annotationMethodCallBeforeListener)
    {
        $this->annotationMethodCallBeforeListener = $annotationMethodCallBeforeListener;
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
