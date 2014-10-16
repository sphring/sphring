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

use Arthurh\Sphring\Enum\BeanTypeEnum;
use Arthurh\Sphring\Enum\SphringEventEnum;
use Arthurh\Sphring\EventDispatcher\EventAnnotation;
use Arthurh\Sphring\EventDispatcher\EventBeanProperty;
use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Exception\BeanException;
use Arthurh\Sphring\Logger\LoggerSphring;
use Arthurh\Sphring\Model\BeanProperty\AbstractBeanProperty;
use zpt\anno\Annotations;


/**
 * Class Bean
 * @package arthurh\sphring\model
 */
class Bean
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $class;

    /**
     * @var BeanTypeEnum
     */
    private $type;

    /**
     * @var AbstractBeanProperty[]
     */
    private $properties = array();

    /**
     * @var Bean
     */
    private $extend;
    /**
     * @var object
     */
    private $object;
    /**
     * @var SphringEventDispatcher
     */
    private $sphringEventDispatcher;

    /**
     * @param $id
     * @param \Arthurh\Sphring\Enum\BeanTypeEnum $type
     */
    function __construct($id, BeanTypeEnum $type = null)
    {
        if ($type == null) {
            $type = BeanTypeEnum::NORMAL_TYPE;
        }
        $this->id = $id;
        $this->type = $type;
    }

    /**
     * @param $key
     */
    public function removeProperty($key)
    {
        if (empty($this->properties[$key])) {
            return;
        }
        unset($this->properties[$key]);
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
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

    /**
     * @param $dataKey
     * @param array $args
     * @throws \Arthurh\Sphring\Exception\BeanException
     */
    private function callSet($dataKey, array $args)
    {
        if (empty($dataKey) || empty($args[0])) {
            throw new BeanException($this, "can't set data '%s' with value '%s' for bean '%'", $dataKey, $args[0], $this->id);
        }
        $this->addProperty($dataKey, $args[0]);
    }

    /**
     * @param $key
     * @param $value
     * @throws \Arthurh\Sphring\Exception\BeanException
     */
    public function addProperty($key, $value)
    {

        if (!is_array($value)) {
            throw new BeanException($this, "Error when declaring property name '%s', property not valid", $key);
        }
        $propertyKey = key($value);
        $event = new EventBeanProperty();
        $event->setData(current($value));
        $eventName = SphringEventEnum::PROPERTY_INJECTION . $propertyKey;
        $event->setName($eventName);
        var_dump($this->getSphringEventDispatcher()->getListeners());
        $event = $this->sphringEventDispatcher->dispatch($eventName, $event);
        $propertyClass = $event->getBeanProperty();
        if (empty($propertyClass)) {
            throw new BeanException($this, "Error when declaring property name '%s', property '%s' doesn't exist", $key, $propertyKey);
        }
        $this->properties[$key] = $propertyClass;
    }

    /**
     * @param $dataKey
     * @return null
     */
    private function callGet($dataKey)
    {
        return $this->getProperty($dataKey);
    }

    /**
     * @param $key
     * @return null
     */
    public function getProperty($key)
    {
        if (empty($this->properties[$key])) {
            return null;
        }
        return $this->properties[$key];
    }

    /**
     * @return BeanTypeEnum
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string|BeanTypeEnum $type
     * @throws \Arthurh\Sphring\Exception\BeanException
     */
    public function setType($type)
    {
        if (empty($type)) {
            return;
        }
        if ($type instanceof BeanTypeEnum) {
            $this->type = $type;
            return;
        }
        $this->type = BeanTypeEnum::fromValue($type);
        if (empty($this->type)) {
            throw new BeanException($this, "Bean type '%s' doesn't exist", $type);
        }
    }

    /**
     * @return Bean
     */
    public function getExtend()
    {
        return $this->extend;
    }

    /**
     * @param Bean $extend
     */
    public function setExtend(Bean $extend)
    {
        $this->extend = $extend;
    }

    public function inject()
    {
        if ($this->type == BeanTypeEnum::ABSTRACT_TYPE) {
            return;
        }
        $this->getLogger()->info(sprintf("Injecting in bean '%s'", $this->id));
        $this->instanciate();
        $properties = $this->properties;
        if (!empty($this->extend)) {
            $properties = array_merge($this->extend->getProperties(), $properties);
        }
        foreach ($properties as $propertyName => $propertyInjector) {
            $setter = "set" . ucfirst($propertyName);
            $this->object->$setter($propertyInjector->getInjection());
        }
        $this->dispatchAnnotations();
    }

    /**
     * @return LoggerSphring
     */
    protected function getLogger()
    {
        return LoggerSphring::getInstance();
    }

    private function dispatchAnnotations()
    {
        $classReflector = new \ReflectionClass($this->class);
        $this->dispatchEventForAnnotation($classReflector, SphringEventEnum::ANNOTATION_CLASS);
        foreach ($classReflector->getMethods() as $methodReflector) {
            $this->dispatchEventForAnnotation($methodReflector, SphringEventEnum::ANNOTATION_METHOD);
        }
    }

    private function instanciate()
    {
        $classReflector = new \ReflectionClass($this->class);
        $this->object = $classReflector->newInstance();
    }

    private function dispatchEventForAnnotation(\Reflector $reflector, $eventNameBase)
    {
        $annotations = new Annotations($reflector);
        $annotationsArray = $annotations->asArray();
        if (empty($annotationsArray)) {
            return;
        }
        foreach ($annotationsArray as $annotationName => $annotationValue) {
            $event = new EventAnnotation();
            $event->setData($annotationValue);
            $event->setBean($this);
            $event->setReflector($reflector);
            $eventName = $eventNameBase . $annotationName;
            $event->setName($eventName);
            $this->sphringEventDispatcher->dispatch($eventName, $event);
        }
    }

    /**
     * @return AbstractBeanProperty[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param AbstractBeanProperty[] $properties
     */
    public function setProperties(array $properties)
    {
        foreach ($properties as $propertyKey => $propertyValue) {
            $this->addProperty($propertyKey, $propertyValue);
        }
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param object $object
     */
    public function setObject($object)
    {
        $this->object = $object;
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
    public function setSphringEventDispatcher($sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
    }

} 