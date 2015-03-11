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
 */
class Foo implements IFoo
{
    private $kiki;
    private $cucu = 'test';
    private $initValue;

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

    public function initialization()
    {
        $this->initValue = "initValue";
    }
}