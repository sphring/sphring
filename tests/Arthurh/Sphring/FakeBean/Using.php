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


class Using implements IUsing
{
    private $jojo;
    private $juju;
    private $foo;
    private $envValue;
    private $envTest;

    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     *
     * @Required
     */
    public function setFoo(IFoo $foo)
    {
        $this->foo = $foo;
    }

    /**
     * @return mixed
     */
    public function getJojo()
    {
        return $this->jojo;
    }

    public function setJojo($stream)
    {
        $this->jojo = $stream;
    }

    /**
     * @return mixed
     */
    public function getJuju()
    {
        return $this->juju;
    }

    /**
     *
     * @Required
     */
    public function setJuju($text)
    {
        $this->juju = $text;
    }

    public function injectValueForTestCall($bean, $value)
    {
        return $value;
    }

    /**
     * @return mixed
     */
    public function getEnvValue()
    {
        return $this->envValue;
    }

    /**
     * @param mixed $envValue
     */
    public function setEnvValue($envValue)
    {
        $this->envValue = $envValue;
    }

    /**
     * @return mixed
     */
    public function getEnvTest()
    {
        return $this->envTest;
    }

    /**
     * @param mixed $envTest
     */
    public function setEnvTest($envTest)
    {
        $this->envTest = $envTest;
    }


}
