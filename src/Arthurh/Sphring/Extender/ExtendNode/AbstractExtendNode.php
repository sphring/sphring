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


namespace Arthurh\Sphring\Extender\ExtendNode;


use Arthurh\Sphring\EventDispatcher\SphringEventDispatcher;
use Arthurh\Sphring\Extender\Node;

/**
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
     * @return Node[]
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @param Node[] $nodes
     */
    public function setNodes(array $nodes)
    {
        foreach ($nodes as $node) {
            $this->addNode($node);
        }
    }

    /**
     * @param Node $node
     */
    public function addNode(Node $node)
    {
        $this->nodes[] = $node;
    }

    /**
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
            unset($this->nodes[$keyToDelete]);
        }
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
    }


    abstract public function extend();
}
