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

namespace Arthurh\Sphring\FakeBean;

/**
 * Class Foo
 * @package Arthurh\Sphring\FakeBean
 * @TestClassInstantiate
 */
class Foo implements IFoo
{
    private $kiki;
    private $cucu = 'test';
    private $initValue;
    private $initValueAnnotation;

    private $testingValue;
    public function __construct($kiki = null)
    {
        $this->kiki = $kiki;
    }

    /**
     * @return string
     */
    public function getCucu()
    {
        return $this->cucu;
    }

    /**
     * @param string $cucu
     */
    public function setCucu($cucu)
    {
        $this->cucu = $cucu;
    }

    /**
     * @return mixed
     */
    public function getKiki()
    {
        return $this->kiki;
    }

    /**
     * @param mixed $kiki
     */
    public function setKiki($kiki)
    {
        $this->kiki = $kiki;
    }

    /**
     * @return mixed
     */
    public function getInitValue()
    {
        return $this->initValue;
    }

    /**
     * @BeforeCall(bean=usebean, method=injectValueForTestCall, return=true)
     * @TestCallBefore(bean=usebean, method=injectValueForTestCall, return=true)
     */
    public function testBeforeCall($value)
    {

    }

    /**
     * @AfterCall(bean=usebean, method=injectValueForTestCall, return=true)
     * @TestCallAfter(bean=usebean, method=injectValueForTestCall, return=true)
     */
    public function testAfterCall($value)
    {

    }

    /**
     * @AfterCall(bean=usebean, method=injectValueForTestCall, return=true, condition=name > 3)
     */
    public function testAfterCallConditionnal($name)
    {
        return $name;
    }

    public function initialization()
    {
        $this->initValue = "initValue";
    }

    /**
     * @MethodInit()
     */
    public function initializationAnnotation()
    {
        $this->initValueAnnotation = "initValue";
    }

    /**
     * @return mixed
     */
    public function getInitValueAnnotation()
    {
        return $this->initValueAnnotation;
    }

    /**
     * @return mixed
     */
    public function getTestingValue()
    {
        return $this->testingValue;
    }

    /**
     * @param mixed $testingValue
     */
    public function setTestingValue($testingValue)
    {
        $this->testingValue = $testingValue;
    }


}