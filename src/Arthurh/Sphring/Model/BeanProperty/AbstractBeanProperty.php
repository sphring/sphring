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

use Arthurh\Sphring\Logger\LoggerSphring;
use Arthurh\Sphring\Sphring;

/**
 * Class AbstractBeanProperty
 * @package arthurh\sphring\model\beanproperty
 */
abstract class AbstractBeanProperty
{
    /**
     * @var Sphring
     */
    protected $sphring;
    /**
     * @var mixed
     */
    protected $data;


    /**
     * @internal param $data
     */
    function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getInjection()
    {
        $this->getLogger()->debug(sprintf("Get injected property..."));
        $inject = $this->inject();
        $this->getLogger()->debug(sprintf("Get injected property finished."));
        return $inject;
    }

    /**
     * @return LoggerSphring
     */
    protected function getLogger()
    {
        return LoggerSphring::getInstance();
    }

    /**
     * @return mixed
     */
    abstract public function inject();

    /**
     * @return Sphring
     */
    public function getSphring()
    {
        return $this->sphring;
    }

    /**
     * @param Sphring $spring
     */
    public function setSphring(Sphring $spring)
    {
        $this->sphring = $spring;
    }

    /**
     * @return array
     */
    public static function getValidation()
    {
        return null;
    }


}
