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
use Arthurh\Sphring\Exception\SphringException;

/**
 * Class BeanPropertyRef
 * @package Arthurh\Sphring\Model\BeanProperty
 */
class BeanPropertyRef extends AbstractBeanProperty
{

    /**
     * @return array|object
     */
    public function inject()
    {
        $beans = $this->getData();
        if (!is_array($beans)) {
            return $this->getBean($beans);
        }
        if (!$this->isAssoc($beans)) {
            $beansArray = array();
            foreach ($beans as $beanId) {
                $beansArray[] = $this->getBean($beanId);
            }
            return $beansArray;
        }
        $beansArray = array();
        foreach ($beans as $key => $beanId) {
            $beansArray[$key] = $this->getBean($beanId);
        }
        return $beansArray;
    }

    /**
     * @param $beanId
     * @return object
     * @throws \Arthurh\Sphring\Exception\BeanPropertyException
     */
    private function getBean($beanId)
    {
        try {
            $bean = $this->sphring->getBeanObject($beanId)->getObject();
        } catch (SphringException $e) {
            throw new BeanPropertyException("Error when injecting a bean inside a bean: %s", $e->getMessage(), $e);
        }
        return $bean;
    }

    /**
     * @param $beans
     * @return bool
     */
    public function isAssoc($beans)
    {
        return array_keys($beans) !== range(0, count($beans) - 1);
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
