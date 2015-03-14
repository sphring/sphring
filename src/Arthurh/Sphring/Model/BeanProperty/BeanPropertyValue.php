<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 14/10/2014
 */

namespace Arthurh\Sphring\Model\BeanProperty;

/**
 * Class BeanPropertyValue
 * @package arthurh\sphring\model\beanproperty
 */
class BeanPropertyValue extends AbstractBeanProperty
{

    /**
     * @return mixed
     */
    public function inject()
    {
        return $this->getData();
    }

    public static function getValidation()
    {
        return null;
    }
}
