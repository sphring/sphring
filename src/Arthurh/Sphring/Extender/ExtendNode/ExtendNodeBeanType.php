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


namespace Arthurh\Sphring\Extender\ExtendNode;


/**
 * Extend bean type
 * Class ExtendNodeBeanType
 * @package Arthurh\Sphring\Extender\ExtendNode
 */
class ExtendNodeBeanType extends AbstractExtendNode
{
    /**
     * @see AbstractExtendNode::extend
     */
    public function extend()
    {
        foreach ($this->nodes as $node) {
            $eventName = $node->getEventName();
            $className = $node->getClassName();
            $this->getSphringEventDispatcher()->getSphring()->getFactoryBean()->addBeanType($eventName, $className);
        }
    }
}
