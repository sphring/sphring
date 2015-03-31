<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 29/10/2014
 */


namespace Arthurh\Sphring\Model\Bean;


use Arthurh\Sphring\Exception\SphringException;
use Arthurh\Sphring\Sphring;
use Arthurh\Sphring\Validation\Validator;
use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

/**
 * Class FactoryBean
 * @package Arthurh\Sphring\Model\Bean
 */
class FactoryBean
{

    /**
     * @var
     */
    private $beansType;
    /**
     * @var Sphring
     */
    private $sphring;


    /**
     * @param Sphring $sphring
     */
    public function __construct(Sphring $sphring)
    {
        $this->sphring = $sphring;
    }

    /**
     * @param $beanId
     * @param $info
     * @return ProxyBean
     * @throws \Arthurh\Sphring\Exception\SphringException
     */
    public function createBean($beanId, $info)
    {
        $beanClass = $this->getType($info['type']);
        $bean = new $beanClass($beanId);
        unset($info['type']);
        if (!($bean instanceof AbstractBean)) {
            throw new SphringException("'%s' is not a valid bean, it must extends '%s'", $beanClass, AbstractBean::class);
        }

        $validator = Validator::getInstance();
        $sphringBoot = $this->sphring->getSphringEventDispatcher()->getSphringBoot();
        $validator->setBeanPropertyListener($sphringBoot->getSphringBootBeanProperty()->getBeanPropertyListener());
        try {
            $validator->validate($bean->getValidBeanFile(), $info);
        } catch (NodeValidatorException $e) {
            echo $bean->getValidBeanFile();
            throw new SphringException("'%s' is not a valid bean: %s", $beanId, $e->getMessage(), $e);
        } catch (SphringException $e) {
            throw new SphringException("'%s' can't be validated: %s", $beanId, $e->getMessage(), $e);
        }


        $bean->setSphringEventDispatcher($this->sphring->getSphringEventDispatcher());
        foreach ($info as $key => $value) {
            $set = 'set' . ucfirst($key);
            $bean->$set($value);
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

    /**
     * @param $type
     * @param $className
     * @throws \Arthurh\Sphring\Exception\SphringException
     */
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

    /**
     * @param $type
     */
    public function removeBeanType($type)
    {
        if (empty($this->beansType[$type])) {
            return;
        }
        unset($this->beansType[$type]);
    }


}
