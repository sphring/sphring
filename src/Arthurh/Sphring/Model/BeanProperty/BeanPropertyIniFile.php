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

use Arthurh\Sphring\Exception\BeanPropertyException;

/**
 * Class BeanPropertyIniFile
 * @package arthurh\sphring\model\beanproperty
 *
 * if given array it must follow env => inifile path
 */
class BeanPropertyIniFile extends AbstractBeanPropertyFileLoader
{

    /**
     *
     */
    public function inject()
    {
        $data = $this->getData();
        $env = null;
        $file = null;
        if (is_array($data)) {
            // key() and current() are break on hhvm
            foreach ($data as $env => $file) {
                break;
            }
        } else {
            $file = $data;
        }
        $file = $this->getFilePath($file);
        if ($file === null) {
            throw new BeanPropertyException("Error when injecting ini in bean, file '%s' doesn't exist.", $file);
        }
        return $this->loadIni($file, $env);
    }

    /**
     * @param $file
     * @param null $env
     * @return \Zend_Config_Ini
     */
    public function loadIni($file, $env = null)
    {
        return new \Zend_Config_Ini($file, $env);
    }

    /**
     * @return array
     */
    public static function getValidation()
    {
        return [
            '_type' => 'choice',
            '_choices' => [
                [
                    '_type' => 'prototype',
                    '_prototype' => [
                        '_type' => 'text'
                    ]
                ],
                [
                    '_type' => 'text'
                ]
            ]
        ];
    }
}
