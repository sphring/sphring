<?php
/**
 * Copyright (C) 2014 Orange
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 29/10/2014
 */


namespace Arthurh\Sphring\Model\Bean;


use Arthurh\Sphring\Enum\BeanTypeEnum;
use Arthurh\Sphring\Exception\BeanException;
use Arthurh\Sphring\Exception\SphringException;
use Arthurh\Sphring\Sphring;

class FactoryBean
{

    private $beansType;
    /**
     * @var Sphring
     */
    private $sphring;

    public function __construct(Sphring $sphring)
    {
        $this->sphring = $sphring;
    }

    public function createBean($beanId, $info)
    {
        $beanClass = $this->getType($info['type']);
        $bean = new $beanClass($beanId);
        if (!($bean instanceof AbstractBean)) {
            throw new SphringException("'%s' is not a valid bean, it must extends '%s'", $beanClass, AbstractBean::class);
        }
        $bean->setSphringEventDispatcher($this->sphring->getSphringEventDispatcher());
        $bean->setClass($info['class']);
        if (!empty($info['properties'])) {
            $bean->setProperties($info['properties']);
        }

        if (!empty($info['extend'])) {
            $bean->setExtend($this->sphring->getBeanObject($info['extend']));
        }
        return $bean;
    }

    /**
     * @param $type
     * @return string
     * @throws \Arthurh\Sphring\Exception\SphringException
     */
    private function getType($type)
    {
        if (empty($type)) {
            return $this->beansType['normal'];
        }
        $type = $this->beansType[$type];
        if (empty($type)) {
            throw new SphringException("Bean type '%s' doesn't exist", $type);
        }
        return $type;
    }

    /**
     * @return Sphring
     */
    public function getSphring()
    {
        return $this->sphring;
    }

    /**
     * @param Sphring $sphring
     */
    public function setSphring($sphring)
    {
        $this->sphring = $sphring;
    }

    /**
     * @return array
     */
    public function getBeansType()
    {
        return $this->beansType;
    }

    /**
     * @param array $beansType
     */
    public function setBeansType(array $beansType)
    {
        foreach ($beansType as $type => $className) {
            $this->addBeanType($type, $className);
        }
    }

    public function addBeanType($type, $className)
    {
        if (!empty($this->beansType[$type])) {
            return;
        }
        if (!class_exists($className)) {
            throw new SphringException("'%s' is not a valid class.", $className);
        }
        $this->beansType[$type] = $className;
    }

    public function removeBeanType($type)
    {
        if (empty($this->beansType[$type])) {
            return;
        }
        unset($this->beansType[$type]);
    }
}
