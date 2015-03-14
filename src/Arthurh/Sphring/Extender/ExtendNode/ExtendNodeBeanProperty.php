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

/**
 * Extend bean property
 * Class ExtendNodeBeanProperty
 * @package Arthurh\Sphring\Extender\ExtendNode
 */
class ExtendNodeBeanProperty extends AbstractExtendNode
{

    /**
     * @see AbstractExtendNode::extend
     */
    public function extend()
    {
        foreach ($this->nodes as $node) {
            $this->getSphringEventDispatcher()->getSphringBoot()->getSphringBootBeanProperty()->getBeanPropertyListener()
                ->register($node->getEventName(), $node->getClassName(), $node->getPriority());
        }
    }
}
