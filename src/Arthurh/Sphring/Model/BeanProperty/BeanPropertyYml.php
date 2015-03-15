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

use Arhframe\Yamlarh\Yamlarh;
use Arthurh\Sphring\Exception\BeanPropertyException;

/**
 * Class BeanPropertyYml
 * @package arthurh\sphring\model\beanproperty
 */
class BeanPropertyYml extends AbstractBeanPropertyFileLoader
{

    /**
     * @return array
     * @throws \arthurh\sphring\exception\BeanPropertyException
     */
    public function inject()
    {
        $file = $this->getData();
        $file = $this->getFilePath($file);
        if ($file === null) {
            throw new BeanPropertyException("Error when injecting yml in bean, file '%s' doesn't exist.", $file);
        }
        return $this->loadYml($file);
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
