<?php
/**
 * Copyright (C) 2014 Orange
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 29/10/2014
 */


namespace Arthurh\Sphring\FakeExtend;


use Arthurh\Sphring\Model\Bean\AbstractBean;

class BeanFake extends AbstractBean
{

    public function inject()
    {
        parent::inject();
        $this->object->testBeanFake = 'testBeanFake';
    }
} 