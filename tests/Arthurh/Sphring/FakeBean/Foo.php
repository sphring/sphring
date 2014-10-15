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


class Foo implements IFoo
{
    private $kiki;
    private $cucu = 'test';

    public function __construct()
    {
        # code...
    }

    /**
     * @return string
     */
    public function getCucu()
    {
        return $this->cucu;
    }

    /**
     * @return mixed
     */
    public function getKiki()
    {
        return $this->kiki;
    }

}