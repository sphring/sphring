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
 * Extend Annotation for method
 * Class ExtendNodeAnnotationMethod
 * @package Arthurh\Sphring\Extender\ExtendNode
 */
class ExtendNodeAnnotationMethod extends AbstractExtendNode
{

    /**
     * @see AbstractExtendNode::extend
     */
    public function extend()
    {
        foreach ($this->nodes as $node) {
            $eventName = $node->getEventName();
            $className = $node->getClassName();
            if (empty($eventName)) {
                $eventName = strtolower($className::getAnnotationName());
            }
            $this->getSphringEventDispatcher()->getSphringBoot()->getSphringBootAnnotation()->getAnnotationMethodListener()
                ->register($eventName, $node->getClassName(), $node->getPriority());
        }
    }
}
