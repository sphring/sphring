<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 15/03/2015
 */

namespace Arthurh\Sphring\Model\BeanProperty;


abstract class AbstractBeanPropertyFileLoader extends AbstractBeanProperty
{
    public function getFilePath($file)
    {
        if (is_file($file)) {
            return $file;
        }
        if (is_file($this->sphring->getRootProject() . $file)) {
            return $this->sphring->getRootProject() . $file;
        }
        if (is_file($this->sphring->getContextRoot() . DIRECTORY_SEPARATOR . $file)) {
            return $this->sphring->getContextRoot() . DIRECTORY_SEPARATOR . $file;
        }
        return null;
    }
}
