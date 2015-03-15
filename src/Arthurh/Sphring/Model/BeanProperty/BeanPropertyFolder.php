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


use Arhframe\Util\Folder;
use Arthurh\Sphring\Exception\BeanPropertyException;

class BeanPropertyFolder extends AbstractBeanProperty
{

    /**
     * @return Folder
     * @throws BeanPropertyException
     */
    public function inject()
    {
        $foldername = $this->getData();
        if (!is_string($foldername)) {
            throw new BeanPropertyException("Error when injecting folder in bean, it's not a valid folder name (not a string).");
        }
        return new Folder($foldername);
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
