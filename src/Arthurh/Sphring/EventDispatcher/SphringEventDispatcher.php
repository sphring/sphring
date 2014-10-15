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


use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class SphringEventDispatcher
 * @package Arthurh\Sphring\EventDispatcher
 */
class SphringEventDispatcher extends EventDispatcher
{
    /**
     * @var SphringEventDispatcher
     */
    private static $_instance = null;

    /**
     * @return SphringEventDispatcher
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new SphringEventDispatcher();
        }

        return self::$_instance;
    }
} 