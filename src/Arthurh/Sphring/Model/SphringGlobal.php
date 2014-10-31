<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 31/10/2014
 */


namespace Arthurh\Sphring\Model;


use Arthurh\Sphring\Sphring;

/**
 * Object for SphringGlobalListerner, extend it for run when sphring trigger an event
 * Class SphringGlobal
 * @package Arthurh\Sphring\Model
 */
abstract class SphringGlobal
{
    /**
     * @var Sphring
     */
    protected $sphring;

    /**
     * @return Sphring
     */
    public function getSphring()
    {
        return $this->sphring;
    }

    /**
     * @param Sphring $sphring
     */
    public function setSphring($sphring)
    {
        $this->sphring = $sphring;
    }

    /**
     * @return mixed
     */
    abstract public function run();

}
