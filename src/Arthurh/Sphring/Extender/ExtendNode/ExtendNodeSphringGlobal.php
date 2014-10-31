<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 31/10/2014
 */


namespace Arthurh\Sphring\Extender\ExtendNode;

use Arthurh\Sphring\Logger\LoggerSphring;

/**
 * Extend sphring global
 * Class ExtendNodeSphringGlobal
 * @package Arthurh\Sphring\Extender\ExtendNode
 */
class ExtendNodeSphringGlobal extends AbstractExtendNode
{

    /**
     * Method which will be called for extend the current Sphring
     * @return mixed
     */
    public function extend()
    {
        LoggerSphring::getInstance()->debug("Running sphring global extend");
        foreach ($this->nodes as $node) {
            $this->getSphringEventDispatcher()->getSphringBoot()->getSphringGlobalListener()
                ->register($node->getEventName(), $node->getClassName(), $node->getPriority());
        }
    }
}
