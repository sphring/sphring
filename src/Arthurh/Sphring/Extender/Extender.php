<?php
/**
 * Copyright (C) 2014 Orange
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

class Extender
{
    /**
     * @var string
     */
    private $defaultFilename = 'sphring-extend.yml';
    const DEFAULT_EXTENDNODE_NAME = 'Arthurh\\Sphring\\Extender\\ExtendNode\\ExtendNode';
    /**
     * @var SphringEventDispatcher
     */
    private $sphringEventDispatcher;

    /**
     * @var AbstractExtendNode[]
     */
    private $extendNodes = array();

    function __construct(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
    }

    public function extend()
    {
        foreach ($this->extendNodes as $extendNode) {
            $extendNode->extend();
        }
    }

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
            $this->AddExtendFromArray($extendNodeName, $extendNodeNameInfo);
        }
    }

    private function makeNode($info)
    {
        if (empty($info['eventName']) || empty($info['class'])) {
            throw new ExtenderException("Can't extend, malformed node.");
        }
        return new Node($info['eventName'], $info['class'], $info['priority']);
    }

    public function AddExtendFromArray($extendNodeName, $extendNodeNameInfo)
    {
        $className = self::DEFAULT_EXTENDNODE_NAME . ucfirst($extendNodeName);

        $extendNode = $this->getExtendNodeFromClassName($className);

        foreach ($extendNodeNameInfo as $info) {
            $extendNode->addNode($this->makeNode($info));
        }

    }

    public function addNode($classNameExtendNode, Node $node)
    {
        $extendNode = $this->getExtendNodeFromClassName($classNameExtendNode);
        $extendNode->addNode($node);
    }

    public function addBeanProperty($propertyClassname, $eventName, $priority = 0)
    {
        $this->addNode(ExtendNodeBeanProperty::class, new Node($eventName, $propertyClassname, $priority));
    }

    public function addAnnotationClass($annotationClassname, $eventName = "", $priority = 0)
    {
        $this->addNode(ExtendNodeAnnotationClass::class, new Node($eventName, $annotationClassname, $priority));
    }

    public function addAnnotationMethod($annotationClassname, $eventName = "", $priority = 0)
    {
        $this->addNode(ExtendNodeAnnotationMethod::class, new Node($eventName, $annotationClassname, $priority));
    }

    function getExtendNodeFromClassName($className)
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
     * @param SphringEventDispatcher $sphringEventDispatcher
     */
    public function setSphringEventDispatcher(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
    }

    /**
     * @return string
     */
    public function getDefaultFilename()
    {
        return $this->defaultFilename;
    }

    /**
     * @param string $defaultFilename
     */
    public function setDefaultFilename($defaultFilename)
    {
        $this->defaultFilename = $defaultFilename;
    }

} 