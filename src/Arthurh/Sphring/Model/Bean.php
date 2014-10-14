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


namespace Arthurh\Sphring\Model;

use Arthurh\Sphring\Exception\BeanException;


/**
 * Class Bean
 * @package arthurh\sphring\model
 */
class Bean
{
    const PROPERTY_NAME = "Arthurh\\Sphring\\Model\\BeanProperty\\BeanProperty";
    /**
     * @var string
     */
    private $id;
    /**
     * @var object
     */
    private $class;
    /**
     * @var bool
     */
    private $isAbstract = false;
    /**
     * @var array
     */
    private $properties;

    /**
     * @param $id
     */
    function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    public function removeProperty($key)
    {
        if (empty($this->properties[$key])) {
            return;
        }
        unset($this->properties[$key]);
    }

    /**
     * @return object
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param object $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return boolean
     */
    public function getIsAbstract()
    {
        return $this->isAbstract;
    }

    /**
     * @param boolean $isAbstract
     */
    public function setIsAbstract($isAbstract)
    {
        $this->isAbstract = $isAbstract;
    }

    /**
     * @param $name
     * @param $args
     */
    public function __call($name, $args)
    {
        $method = substr($name, 0, 3);
        $dataKey = lcfirst(substr($name, 3, strlen($name) - 3));

        if ($method == 'set') {
            $this->callSet($dataKey, $args);
            return;
        } elseif ($method == 'get') {
            return $this->callGet($dataKey);
        }
        throw new BeanException($this, "method '%s' doesn't exist", $name);
    }

    private function callSet($dataKey, array $args)
    {
        if (empty($dataKey) || empty($args[0])) {
            throw new BeanException($this, "can't set data '%s' with value '%s' for bean '%'", $dataKey, $args[0], $this->id);
        }
        $this->addProperty($dataKey, $args[0]);
    }

    public function addProperty($key, $value)
    {
        if (!is_array($value)) {
            throw new BeanException($this, "Error when declaring property name '%s', property not valid", $key);
        }
        $propertyName = self::PROPERTY_NAME . ucfirst(key($value));
        try {
            $property = new \ReflectionClass($propertyName);
            $propertyClass = $property->newInstance(current($value));
            $this->properties[$key] = $propertyClass;
        } catch (\Exception $e) {
            throw new BeanException($this, "Error when declaring property name '%s', property '%s' doesn't exist", $key, $propertyName, $e);
        }

    }

    private function callGet($dataKey)
    {
        return $this->getProperty($dataKey);
    }

    public function getProperty($key)
    {
        if (empty($this->properties[$key])) {
            return null;
        }
        return $this->properties[$key];
    }
} 