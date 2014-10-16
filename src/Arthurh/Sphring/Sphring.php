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

/**
 * instropection composer:
 * $factoryComposer = new \Composer\Factory();
 * $composer = $factoryComposer->createComposer(new \Composer\IO\NullIO());
 * var_dump($composer->getPackage()->getRequires());
 */

namespace Arthurh\Sphring;


use Arhframe\Yamlarh\Yamlarh;
use Arthurh\Sphring\Enum\SphringEventEnum;
use Arthurh\Sphring\EventDispatcher\EventSphring;
use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Exception\SphringException;
use Arthurh\Sphring\Extender\Extender;
use Arthurh\Sphring\Logger\LoggerSphring;
use Arthurh\Sphring\Model\Bean;
use Psr\Log\LoggerInterface;

/**
 * Class Sphring
 * @package Arthurh\Sphring
 */
class Sphring
{
    private $filename;
    /**
     *
     */
    const DEFAULT_CONTEXT_FOLDER = 'sphring';
    /**
     *
     */
    const DEFAULT_CONTEXT_FILE = 'main.yml';
    /**
     * @var null
     */
    private $contextRoot = null;
    /**
     * @var array
     */
    private $context = array();
    /**
     * @var Bean[]
     */
    private $beans = array();


    /**
     * @var SphringEventDispatcher
     *
     */
    private $sphringEventDispatcher;

    /**
     * @var Extender
     */
    private $extender;

    /**
     *
     */
    public function __construct($filename = null)
    {
        if (empty($filename)) {
            $filename = '/' . self::DEFAULT_CONTEXT_FOLDER . '/' . self::DEFAULT_CONTEXT_FILE;
        }
        $this->filename = $filename;
        $this->sphringEventDispatcher = new SphringEventDispatcher($this);
        $this->extender = new Extender($this->sphringEventDispatcher);

    }


    /**
     * @param $filename
     */
    public function loadContext()
    {

        $this->sphringEventDispatcher->dispatch(SphringEventEnum::SPHRING_BEFORE_LOAD, new EventSphring($this));
        $filename = $this->filename;
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
        $this->contextRoot = dirname(realpath($filename));
        $this->getLogger()->info(sprintf("Loading context '%s' ...", realpath($filename)));
        $this->context = $yamlarh->parse();
        $this->extender->addExtendFromFile($this->contextRoot . '/' . $this->extender->getDefaultFilename());

        $this->extender->extend();
        $this->sphringEventDispatcher->dispatch(SphringEventEnum::SPHRING_START_LOAD, new EventSphring($this));
        $this->loadBeans();
        $this->sphringEventDispatcher->dispatch(SphringEventEnum::SPHRING_FINISHED_LOAD, new EventSphring($this));
    }

    /**
     * @return LoggerSphring
     */
    protected function getLogger()
    {
        return LoggerSphring::getInstance();
    }

    /**
     * @return string
     */
    public function getRootProject()
    {
        return dirname($_SERVER['SCRIPT_FILENAME']);
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
     * @param $info
     * @return Bean
     */
    private function makeBean($beanId, $info)
    {
        $bean = new Bean($beanId);
        $bean->setSphringEventDispatcher($this->sphringEventDispatcher);
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
     * @param Bean $bean
     */
    public function addBean(Bean $bean)
    {
        $this->beans[$bean->getId()] = $bean;
        $bean->inject();
    }

    public function clear()
    {
        $this->sphringEventDispatcher->dispatch(SphringEventEnum::SPHRING_CLEAR, new EventSphring($this));
        $this->context = array();
        $this->beans = array();
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
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        LoggerSphring::getInstance()->setLogger($logger);

    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return null
     */
    public function getContextRoot()
    {
        return $this->contextRoot;
    }

    /**
     * @param null $contextroot
     */
    public function setContextRoot($contextroot)
    {
        $this->contextRoot = $contextroot;
    }


    /**
     * @return SphringEventDispatcher
     */
    public function getSphringEventDispatcher()
    {
        return $this->sphringEventDispatcher;
    }

    /**
     * @param SphringEventDispatcher $sphringEventDispatcher
     */
    public function setSphringEventDispatcher(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
        $this->sphringEventDispatcher->setSphring($this);
    }

    /**
     * @return Extender
     */
    public function getExtender()
    {
        return $this->extender;
    }

    /**
     * @param Extender $extender
     */
    public function setExtender(Extender $extender)
    {
        $this->extender = $extender;
        $this->extender->setSphringEventDispatcher($this->sphringEventDispatcher);
    }

    public function addBeanProperty($propertyClassname, $eventName, $priority = 0)
    {
        $this->extender->addBeanProperty($propertyClassname, $eventName, $priority);
    }

    public function addAnnotationClass($annotationClassname, $eventName = "", $priority = 0)
    {
        $this->extender->addAnnotationClass($annotationClassname, $eventName, $priority);
    }

    public function addAnnotationMethod($annotationClassname, $eventName = "", $priority = 0)
    {
        $this->extender->addAnnotationMethod($annotationClassname, $eventName, $priority);
    }

}