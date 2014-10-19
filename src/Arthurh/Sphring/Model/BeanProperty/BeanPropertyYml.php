<?php
/**
 * Copyright (C) 2014 Orange
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 14/10/2014
 */

namespace Arthurh\Sphring\Model\BeanProperty;

use Arhframe\Yamlarh\Yamlarh;
use Arthurh\Sphring\Exception\BeanPropertyException;

/**
 * Class BeanPropertyYml
 * @package arthurh\sphring\model\beanproperty
 */
class BeanPropertyYml extends AbstractBeanProperty
{

    /**
     * @return array
     * @throws \arthurh\sphring\exception\BeanPropertyException
     */
    public function inject()
    {
        $file = $this->getData();
        if (is_file($file)) {
            return $this->loadYml($file);
        }
        echo $this->sphring->getRootProject() . $file;
        if (is_file($this->sphring->getRootProject() . $file)) {
            return $this->loadYml($this->sphring->getRootProject() . $file);
        }
        if (is_file($this->sphring->getContextRoot() . DIRECTORY_SEPARATOR . $file)) {
            return $this->loadYml($this->sphring->getContextRoot() . DIRECTORY_SEPARATOR . $file);
        }
        throw new BeanPropertyException("Error when injecting yml in bean, file '%s' doesn't exist.", $file);
    }

    /**
     * @param $file
     * @return array
     */
    public function loadYml($file)
    {
        $yamlArh = new Yamlarh($file);
        return $yamlArh->parse();
    }
}
