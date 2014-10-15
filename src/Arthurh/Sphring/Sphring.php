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


namespace Arthurh\Sphring;


use Arhframe\Yamlarh\Yamlarh;
use Arthurh\Sphring\Exception\SphringException;
use Arthurh\Sphring\Logger\LoggerSphring;
use Arthurh\Sphring\Model\Bean;
use Psr\Log\LoggerInterface;

/**
 * Class Sphring
 * @package Arthurh\Sphring
 */
class Sphring
{
    /**
     *
     */
    const DEFAULT_CONTEXT_FOLDER = 'context';
    /**
     *
     */
    const DEFAULT_CONTEXT_FILE = 'main.yml';
    /**
     * @var null
     */
    public static $CONTEXTROOT = null;
    /**
     * @var Sphring
     */
    private static $_instance = null;
    /**
     * @var array
     */
    private $context = array();
    /**
     * @var Bean[]
     */
    private $beans = array();

    /**
     *
     */
    private function __construct()
    {

    }

    /**
     * @return Sphring
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Sphring();
        }

        return self::$_instance;
    }

    /**
     * @param $filename
     */
    public function loadContext($filename = null)
    {
        $this->context = array();
        $this->beans = array();
        $this->getLogger()->info("Starting loading context...");
        if (empty($filename)) {
            $filename = $this->getRootProject() . '/' . self::DEFAULT_CONTEXT_FOLDER . '/' . self::DEFAULT_CONTEXT_FILE;
        }
        $yamlarh = null;
        if (is_file($filename)) {
            $yamlarh = new Yamlarh($filename);
        }
        if (is_file($this->getRootProject() . $filename)) {
            $filename = $this->getRootProject() . $filename;
            $yamlarh = new Yamlarh($filename);
        }
        if (empty($yamlarh)) {
            throw new SphringException("Cannot load context, file '%s' doesn't exist", $filename);
        }
        self::$CONTEXTROOT = dirname(realpath($filename));
        $this->getLogger()->info(sprintf("Loading context '%s' ...", realpath($filename)));
        $this->context = $yamlarh->parse();
        $this->loadBeans();
    }

    /**
     *
     */
    private function loadBeans()
    {
        foreach ($this->context as $beanId => $info) {
            if (!empty($this->beans[$beanId])) {
                continue;
            }
            $bean = $this->makeBean($beanId, $this->context[$beanId]);
            $this->addBean($bean);

        }
    }

    /**
     * @param $beanId
     * @return Bean
     * @throws Exception\SphringException
     */
    public function getBeanObject($beanId)
    {
        if (!empty($this->beans[$beanId])) {
            return $this->beans[$beanId];
        }
        if (empty($this->context[$beanId])) {
            throw new SphringException("Bean '%s' doesn't exist in the context.", $beanId);
        }
        $bean = $this->makeBean($beanId, $this->context[$beanId]);
        $this->addBean($bean);
        return $bean;
    }

    /**
     * @param $beanId
     * @return object
     * @throws Exception\SphringException
     */
    public function getBean($beanId)
    {
        if (empty($this->beans[$beanId])) {
            throw new SphringException("Bean '%s' doesn't exist in the context.", $beanId);
        }
        return $this->beans[$beanId]->getObject();
    }

    /**
     * @param Bean $bean
     */
    public function addBean(Bean $bean)
    {
        $this->beans[$bean->getId()] = $bean;
        $bean->inject();
    }

    /**
     * @param $bean
     */
    public function removeBean($bean)
    {
        if ($bean instanceof Bean) {
            $beanId = $bean->getId();
        } else {
            $beanId = $bean;
        }
        if (empty($this->beans[$beanId])) {
            return;
        }
        unset($this->beans[$beanId]);
    }

    /**
     * @param $beanId
     * @param $info
     * @return Bean
     */
    private function makeBean($beanId, $info)
    {
        $bean = new Bean($beanId);
        $bean->setClass($info['class']);
        $bean->setType($info['type']);
        if (!empty($info['properties'])) {
            $bean->setProperties($info['properties']);
        }

        if (!empty($info['extend'])) {
            $bean->setExtend($this->getBeanObject($info['extend']));
        }
        return $bean;
    }

    /**
     * @return string
     */
    public function getRootProject()
    {
        return dirname($_SERVER['SCRIPT_FILENAME']);
    }


    /**
     * @return LoggerSphring
     */
    protected function getLogger()
    {
        return LoggerSphring::getInstance();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        LoggerSphring::getInstance()->setLogger($logger);

    }
} 