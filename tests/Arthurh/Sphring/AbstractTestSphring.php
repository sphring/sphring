<?php
namespace Arthurh\Sphring;

/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 14/10/2014
 */
class AbstractTestSphring extends \PHPUnit_Framework_TestCase
{
    public static $RESOURCE_FOLDER;
    public static $CONTEXT_FOLDER;
    public static $CONTEXT_EXTEND_FOLDER;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        self::$RESOURCE_FOLDER = __DIR__ . '/Resources';
        self::$CONTEXT_FOLDER = self::$RESOURCE_FOLDER . '/sphring';
        self::$CONTEXT_EXTEND_FOLDER = self::$CONTEXT_FOLDER . '/extend';
    }

    /**
     * @return \Arthurh\Sphring\Logger\LoggerSphring
     */
    protected function getLogger()
    {
        return \Arthurh\Sphring\Logger\LoggerSphring::getInstance();
    }
} 