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

namespace Arthurh\Sphring\Extender\ExtendNode;

use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Extender\Node;

/**
 * This class is an abstract class to permit extension easily on sphring.
 * Class AbstractExtendNode
 * @package Arthurh\Sphring\Extender\ExtendNode
 */
abstract class AbstractExtendNode
{
    /**
     * @var Node[]
     */
    protected $nodes;

    /**
     * @var SphringEventDispatcher
     */
    private $sphringEventDispatcher;

    /**
     *
     */
    public function __construct()
    {

    }

    /**
     * Return all nodes referenced in ExtendNode
     * @return Node[]
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * Set Nodes to reference
     * @param Node[] $nodes
     */
    public function setNodes(array $nodes)
    {
        $this->nodes = $nodes;
    }

    /**
     * Add a Node in this ExtendNode
     * @param Node $node
     */
    public function addNode(Node $node)
    {
        $this->nodes[] = $node;
    }

    /**
     * Remove a Node in this ExtendNode
     * @param Node $nodeOrigin
     */
    public function removeNode(Node $nodeOrigin)
    {
        $keyToDelete = null;
        foreach ($this->nodes as $key => $node) {
            if ($nodeOrigin === $node) {
                $keyToDelete = $key;
                break;
            }
        }
        if ($keyToDelete !== null) {
            array_splice($this->nodes, $keyToDelete, 1);
        }
    }

    /**
     * Return the SphringEventDispatcher
     * @return SphringEventDispatcher
     */
    public function getSphringEventDispatcher()
    {
        return $this->sphringEventDispatcher;
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
     * Method which will be called for extend the current Sphring
     * @return mixed
     */
    abstract public function extend();
}
