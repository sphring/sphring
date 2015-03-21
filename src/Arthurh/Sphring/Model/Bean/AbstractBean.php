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

use Arthurh\Sphring\Enum\SphringEventEnum;
use Arthurh\Sphring\EventDispatcher\AnnotationsDispatcher;
use Arthurh\Sphring\EventDispatcher\EventBeanProperty;
use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Exception\BeanException;
use Arthurh\Sphring\Logger\LoggerSphring;
use Arthurh\Sphring\Model\BeanProperty\AbstractBeanProperty;

/**
 * Class AbstractBean
 * @package Arthurh\Sphring\Model\Bean
 */
abstract class AbstractBean
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $class;

    /**
     * @var AbstractBeanProperty[]
     */
    protected $properties = array();

    /**
     * @var AbstractBeanProperty[]
     */
    protected $constructor = array();
    /**
     * @var Bean
     */
    protected $extend;
    /**
     * @var object
     */
    protected $object;
    /**
     * @var SphringEventDispatcher
     */
    protected $sphringEventDispatcher;

    /**
     * @var array
     */
    protected $interfaces = [];
    /**
     * @var string
     */
    protected $parent;

    /**
     * @var string
     */
    protected $methodInit;

    /**
     * @param string $id
     */
    function __construct($id)
    {
        $this->id = $id;
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
     * @param string $key
     * @return null|AbstractBeanProperty
     */
    public function getProperty($key)
    {
        if (empty($this->properties[$key])) {
            return null;
        }
        return $this->properties[$key];
    }

    /**
     * @return Bean
     */
    public function getExtend()
    {
        return $this->extend;
    }

    /**
     * @param string|AbstractBean $extend
     */
    public function setExtend($extend)
    {
        if (is_string($extend)) {
            $extend = $this->getSphringEventDispatcher()->getSphring()->getBeanObject($extend);
        }
        $this->extend = $extend;
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

    /**
     *
     */
    public function inject()
    {
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

    /**
     *
     */
    protected function instanciate()
    {
        $annotationDispatcher = new AnnotationsDispatcher($this, $this->getClass(), $this->getSphringEventDispatcher());
        $classReflector = new \ReflectionClass($this->class);
        if (empty($this->constructor)) {
            $this->object = $classReflector->newInstance();
            $annotationDispatcher->dispatchAnnotationClassInstantiate();
            return;
        }
        $constructor = $this->constructor;
        if (!empty($this->extend)) {
            $constructor = array_merge($this->extend->getConstructor(), $constructor);
        }
        $this->object = $classReflector->newInstanceArgs($constructor);
        $annotationDispatcher->setMethodArgs($constructor);
        $annotationDispatcher->dispatchAnnotationClassInstantiate();
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
        try {
            $reflector = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            return;
        }

        $this->interfaces = $reflector->getInterfaceNames();
        if (!empty($reflector->getParentClass())) {
            $this->parent = $reflector->getParentClass()->getName();
        }

    }

    /**
     * @return \Arthurh\Sphring\Model\BeanProperty\AbstractBeanProperty[]
     */
    public function getConstructor()
    {
        return $this->constructor;
    }

    /**
     * @param \Arthurh\Sphring\Model\BeanProperty\AbstractBeanProperty[] $constructor
     * @throws BeanException
     */
    public function setConstructor($constructor)
    {
        foreach ($constructor as $constructorData) {
            $constructorKey = null;
            $constructorValue = null;
            foreach ($constructorData as $constructorKey => $constructorValue) {
                break;
            }
            try {
                $propertyClass = $this->getPropertyFromEvent($constructorKey, $constructorValue);
                $this->constructor[] = $propertyClass->inject();
            } catch (BeanException $e) {
                throw new BeanException($this, "Error when declaring constructor: '%s'.", $e->getMessage());
            }
            if (empty($propertyClass)) {
                throw new BeanException($this, "Error when declaring constructor, property '%s' doesn't exist", $constructorKey);
            }
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
     * @param array $properties
     */
    public function setProperties(array $properties)
    {
        foreach ($properties as $propertyKey => $propertyValue) {
            $this->addProperty($propertyKey, $propertyValue);
        }
    }

    /**
     *
     */
    protected function dispatchAnnotations()
    {
        $annotationDispatcher = new AnnotationsDispatcher($this, $this->class, $this->sphringEventDispatcher);
        $annotationDispatcher->dispatchAnnotations();
    }

    /**
     * @param string $propertyKey
     * @param mixed $propertyValue
     * @throws \Arthurh\Sphring\Exception\BeanException
     * @return AbstractBeanProperty
     */
    protected function getPropertyFromEvent($propertyKey, $propertyValue)
    {
        if (!isset($propertyValue)) {
            throw new BeanException($this, "property not valid");
        }
        $event = new EventBeanProperty();
        $event->setData($propertyValue);
        $eventName = SphringEventEnum::PROPERTY_INJECTION . $propertyKey;
        $event->setName($eventName);

        $event = $this->sphringEventDispatcher->dispatch($eventName, $event);
        if (!($event instanceof EventBeanProperty)) {
            throw new BeanException($this, "event '%s' is not a '%s' event", get_class($event), EventBeanProperty::class);
        }
        return $event->getBeanProperty();
    }

    /**
     * @param $key
     * @param array $value
     * @throws \Arthurh\Sphring\Exception\BeanException
     */
    public function addProperty($key, array $value)
    {
        $propertyKey = null;
        $propertyValue = null;
        // key() and current() are break on hhvm do it in other way the same thing
        foreach ($value as $propertyKey => $propertyValue) {
            break;
        }
        try {
            $propertyClass = $this->getPropertyFromEvent($propertyKey, $propertyValue);
        } catch (BeanException $e) {
            throw new BeanException($this, "Error when declaring property name '%s': '%s'.", $key, $e->getMessage());
        }
        if (empty($propertyClass)) {
            throw new BeanException($this, "Error when declaring property name '%s', property '%s' doesn't exist", $key, $propertyKey);
        }
        $this->properties[$key] = $propertyClass;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param \Arthurh\Sphring\Runner\SphringRunner $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @return array
     */
    public function getInterfaces()
    {
        return $this->interfaces;
    }

    /**
     * @param null|\ReflectionClass $className
     * @return bool
     */
    public function containClassName($className)
    {
        return $this->hasInterface($className) || $this->hasParent($className) || $this->class == $className;
    }

    /**
     * @param string $interfaceName
     * @return bool
     */
    public function hasInterface($interfaceName)
    {
        return in_array($interfaceName, $this->interfaces);
    }

    /**
     * @param string $className
     * @return bool
     */
    public function hasParent($className)
    {
        return $this->getParent() == $className;
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getMethodInit()
    {
        return $this->methodInit;
    }

    /**
     * @param string $methodInit
     */
    public function setMethodInit($methodInit)
    {
        $this->methodInit = $methodInit;
    }

    abstract public function getValidBeanFile();
}
