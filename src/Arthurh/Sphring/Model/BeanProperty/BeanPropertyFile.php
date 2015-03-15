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

namespace Arthurh\Sphring\Model\BeanProperty;


use Arhframe\Util\File;
use Arthurh\Sphring\Exception\BeanPropertyException;

class BeanPropertyFile extends AbstractBeanProperty
{

    /**
     * @return File
     * @throws BeanPropertyException
     */
    public function inject()
    {
        $filename = $this->getData();
        if (!is_string($filename)) {
            throw new BeanPropertyException("Error when injecting file in bean, it's not a valid file name (not a string).");
        }
        return new File($filename);
    }

    /**
     * @return array
     */
    public static function getValidation()
    {
        return [
            '_type' => 'text'
        ];
    }
}
