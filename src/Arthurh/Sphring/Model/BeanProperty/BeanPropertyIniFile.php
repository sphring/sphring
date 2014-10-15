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

use Arthurh\Sphring\Exception\BeanPropertyException;
use Arthurh\Sphring\Sphring;


/**
 * Class BeanPropertyIniFile
 * @package arthurh\sphring\model\beanproperty
 *
 * if given array it must follow env => inifile path
 */
class BeanPropertyIniFile extends AbstractBeanProperty
{

    /**
     *
     */
    public function inject()
    {
        $data = $this->getData();
        $env = null;
        if (is_array($data)) {
            $env = key($data);
            $file = current($data);
        } else {
            $file = $data;
        }
        if (is_file($file)) {
            return $this->loadIni($file, $env);
        }
        if (is_file(Sphring::getInstance()->getRootProject() . $file)) {
            return $this->loadIni(Sphring::getInstance()->getRootProject() . $file, $env);
        }
        if (is_file(Sphring::$CONTEXTROOT . '/' . $file)) {
            return $this->loadIni(Sphring::$CONTEXTROOT . '/' . $file, $env);
        }
        throw new BeanPropertyException("Error when injecting ini in bean, file '%s' doesn't exist.", $file);
    }

    public function loadIni($file, $env = null)
    {
        return new \Zend_Config_Ini($file, $env);
    }
}