<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 15/10/2014
 */

namespace Arthurh\Sphring\Extender;

use Arhframe\Yamlarh\Yamlarh;
use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Exception\ExtenderException;
use Arthurh\Sphring\Extender\ExtendNode\AbstractExtendNode;
use Arthurh\Sphring\Extender\ExtendNode\ExtendNodeAnnotationClass;
use Arthurh\Sphring\Extender\ExtendNode\ExtendNodeAnnotationMethod;
use Arthurh\Sphring\Extender\ExtendNode\ExtendNodeBeanProperty;
use Arthurh\Sphring\Extender\ExtendNode\ExtendNodeSphringGlobal;

/**
 * Class which will extend the current Sphring
 * Class Extender
 * @package Arthurh\Sphring\Extender
 */
class Extender
{
    /**
     *
     */
    const DEFAULT_EXTENDNODE_NAME = 'Arthurh\\Sphring\\Extender\\ExtendNode\\ExtendNode';
    /**
     * @var string
     */
    private $defaultFilename = 'sphring-extend.yml';
    /**
     * @var SphringEventDispatcher
     */
    private $sphringEventDispatcher;

    /**
     * @var AbstractExtendNode[]
     */
    private $extendNodes = array();

    /**
     * Constructor
     * @param SphringEventDispatcher $sphringEventDispatcher
     */
    public function __construct(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;

    }

    /**
     * Extend from all extend nodes referenced
     */
    public function extend()
    {
        foreach ($this->extendNodes as $extendNode) {
            $extendNode->extend();
        }
    }

    /**
     * Extend from a yml file
     * @param string $file
     */
    public function addExtendFromFile($file)
    {
        if (!is_file($file)) {
            return;
        }
        $yamlarh = new Yamlarh($file);
        if (empty($yamlarh)) {
            return;
        }
        $parsedYml = $yamlarh->parse();
        foreach ($parsedYml as $extendNodeName => $extendNodeNameInfo) {
            $this->addExtendFromArray($extendNodeName, $extendNodeNameInfo);
        }
    }

    /**
     * Extend from an array
     * @param $extendNodeName
     * @param $extendNodeNameInfo
     */
    public function addExtendFromArray($extendNodeName, $extendNodeNameInfo)
    {
        $className = self::DEFAULT_EXTENDNODE_NAME . ucfirst($extendNodeName);

        $extendNode = $this->getExtendNodeFromClassName($className);

        foreach ($extendNodeNameInfo as $info) {
            $extendNode->addNode($this->makeNode($info));
        }

    }

    /**
     * Extend from an AbstractExtendNode implementation
     * @param $className
     * @return AbstractExtendNode
     * @throws \Arthurh\Sphring\Exception\ExtenderException
     */
    public function getExtendNodeFromClassName($className)
    {
        if (!empty($this->extendNodes[$className])) {
            return $this->extendNodes[$className];
        }
        try {
            $classReflected = new \ReflectionClass($className);
            $extendNode = $classReflected->newInstance();

        } catch (\Exception $e) {
            throw new ExtenderException("Can't extend '%s' extend node class '%s' doesn't exist.", $className, $className);
        }
        if (!($extendNode instanceof AbstractExtendNode)) {
            throw new ExtenderException("Can't extend '%s' extend node class '%s' not extends '%s'.", $className, $className, "AbstractExtendNode");
        }
        $extendNode->setSphringEventDispatcher($this->sphringEventDispatcher);
        $this->extendNodes[$className] = $extendNode;
        return $extendNode;
    }

    /**
     * Create node from array of info
     * @param $info
     * @return Node
     * @throws \Arthurh\Sphring\Exception\ExtenderException
     */
    private function makeNode($info)
    {
        if (empty($info['eventName']) || empty($info['class'])) {
            throw new ExtenderException("Can't extend, malformed node.");
        }
        return new Node($info['eventName'], $info['class'], $info['priority']);
    }

    /**
     * Add bean property extension with an ExtendNodeBeanProperty
     * @param $propertyClassname
     * @param $eventName
     * @param int $priority
     */
    public function addBeanProperty($propertyClassname, $eventName, $priority = 0)
    {
        $this->addNode(ExtendNodeBeanProperty::class, new Node($eventName, $propertyClassname, $priority));
    }

    /**
     * Add node extension to an AbstractExtendNode
     * @param $classNameExtendNode
     * @param Node $node
     */
    public function addNode($classNameExtendNode, Node $node)
    {
        $extendNode = $this->getExtendNodeFromClassName($classNameExtendNode);
        $extendNode->addNode($node);
    }

    /**
     * Add annotation class extension with an ExtendNodeAnnotationClass
     * @param $annotationClassname
     * @param string $eventName
     * @param int $priority
     */
    public function addAnnotationClass($annotationClassname, $eventName = "", $priority = 0)
    {
        $this->addNode(ExtendNodeAnnotationClass::class, new Node($eventName, $annotationClassname, $priority));
    }

    /**
     * Add annotation method extension with an ExtendNodeAnnotationMethod
     * @param $annotationClassname
     * @param string $eventName
     * @param int $priority
     */
    public function addAnnotationMethod($annotationClassname, $eventName = "", $priority = 0)
    {
        $this->addNode(ExtendNodeAnnotationMethod::class, new Node($eventName, $annotationClassname, $priority));
    }

    /**
     * Add sphring global extension with an ExtendNodeSphringGlobal
     * @param $sphringGlobalClassname
     * @param string $eventName
     * @param int $priority
     */
    public function addSphringGlobal($sphringGlobalClassname, $eventName = "", $priority = 0)
    {
        $this->addNode(ExtendNodeSphringGlobal::class, new Node($eventName, $sphringGlobalClassname, $priority));
    }

    /**
     * Set the SphringEventDispatcher
     * @param SphringEventDispatcher $sphringEventDispatcher
     */
    public function setSphringEventDispatcher(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
    }

    /**
     * Get the default file name use to extend
     * @return string
     */
    public function getDefaultFilename()
    {
        return $this->defaultFilename;
    }

    /**
     * Set the default file name use to extend
     * @param string $defaultFilename
     */
    public function setDefaultFilename($defaultFilename)
    {
        $this->defaultFilename = $defaultFilename;
    }

    /**
     * Get the list of AbstractExtendNode
     * @return ExtendNode\AbstractExtendNode[]
     */
    public function getExtendNodes()
    {
        return $this->extendNodes;
    }


}
