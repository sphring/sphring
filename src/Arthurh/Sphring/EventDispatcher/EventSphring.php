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

namespace Arthurh\Sphring\EventDispatcher;

use Arthurh\Sphring\Sphring;

/**
 * Event passed when triggering sphring event
 * Class EventSphring
 * @package Arthurh\Sphring\EventDispatcher
 */
class EventSphring extends AbstractSphringEvent
{

    /**
     * @var Sphring
     */
    private $sphring;

    /**
     * Constructor
     * @param Sphring $sphring
     */
    public function __construct(Sphring $sphring)
    {
        $this->sphring = $sphring;
    }

    /**
     * Return the Sphring object
     * @return Sphring
     */
    public function getSphring()
    {
        return $this->sphring;
    }

    /**
     * Set the Sphring object
     * @param Sphring $sphring
     */
    public function setSphring($sphring)
    {
        $this->sphring = $sphring;
    }

}
