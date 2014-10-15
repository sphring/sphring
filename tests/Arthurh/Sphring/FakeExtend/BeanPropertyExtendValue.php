<?php
/**
 * Copyright (C) 2014 Orange
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 15/10/2014
 */


namespace Arthurh\Sphring\FakeExtend;


use Arthurh\Sphring\Model\BeanProperty\AbstractBeanProperty;
use Arthurh\Sphring\Model\BeanProperty\BeanPropertyValue;

class BeanPropertyExtendValue extends AbstractBeanProperty
{

    /**
     * @return mixed
     */
    public function inject()
    {
        $data = $this->getData();
        if (!is_array($data)) {
            $data = array($data => $data);
        }
        $data['testExtend'] = 'testExtend';
        return $data;
    }
}