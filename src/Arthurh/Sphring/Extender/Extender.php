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

    function __construct(SphringEventDispatcher $sphringEventDispatcher)
    {
        $this->sphringEventDispatcher = $sphringEventDispatcher;
    }

    public function extend($file)
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
            $className = self::DEFAULT_EXTENDNODE_NAME . ucfirst($extendNodeName);
            try {
                $classReflected = new \ReflectionClass($className);
                $extendNode = $classReflected->newInstance();
            } catch (\Exception $e) {
                throw new ExtenderException("Can't extend '%s' extend node class '%s' doesn't exist.", $extendNodeName, $className);
            }
            if (!($extendNode instanceof AbstractExtendNode)) {
                throw new ExtenderException("Can't extend '%s' extend node class '%s' not extends '%s'.", $extendNodeName, $className, "AbstractExtendNode");
            }
            $extendNode->setSphringEventDispatcher($this->sphringEventDispatcher);
            foreach ($extendNodeNameInfo as $info) {
                if (empty($info['eventName']) || empty($info['class'])) {
                    throw new ExtenderException("Can't extend '%s', malformed node.", $extendNodeName);
                }
                $extendNode->addNode(new Node($info['eventName'], $info['class'], $info['priority']));
            }
            $extendNode->extend();
        }
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