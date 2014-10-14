<?php
/**
 * Copyright (C) 2014 Orange
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 14/10/2014
 */


namespace Arthurh\Sphring;


use Arhframe\Util\File;
use Arhframe\Yamlarh\Yamlarh;
use Arthurh\Sphring\Exception\SphringException;

/**
 * Class Sphring
 * @package Arthurh\Sphring
 */
class Sphring
{
    /**
     * @var null
     */
    public static $CONTEXTROOT = null;
    /**
     * @var Sphring
     */
    private static $_instance = null;
    /**
     * @var array
     */
    private $context = array();
    /**
     * @var
     */
    private $beans;

    /**
     *
     */
    private function __construct()
    {

    }

    /**
     * @return Sphring
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Sphring();
        }

        return self::$_instance;
    }

    /**
     * @param $filename
     */
    public function loadContext($filename)
    {
        $yamlarh = null;
        if (is_file($filename)) {
            $yamlarh = new Yamlarh($filename);
        }
        if (is_file($this->getRootProject() . $filename)) {
            $filename = $this->getRootProject() . $filename;
            $yamlarh = new Yamlarh($filename);
        }
        if (empty($yamlarh)) {
            throw new SphringException("Cannot load context, file '%s' doesn't exist", $filename);
        }
        self::$CONTEXTROOT = dirname(realpath($filename));
    }

    /**
     * @return string
     */
    public function getRootProject()
    {
        return dirname($_SERVER['SCRIPT_FILENAME']);
    }

    public function loadContextYml($filename)
    {

    }
} 