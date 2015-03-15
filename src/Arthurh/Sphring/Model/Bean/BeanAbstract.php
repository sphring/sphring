<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 29/10/2014
 */


namespace Arthurh\Sphring\Model\Bean;


/**
 * Class BeanAbstract
 * @package Arthurh\Sphring\Model\Bean
 */
class BeanAbstract extends AbstractBean
{
    /**
     *
     */
    public function inject()
    {
        return;
    }

    public function getValidBeanFile()
    {
        return __DIR__ . '/../../Validation/Bean/beanAbstract.yml';
    }
}
