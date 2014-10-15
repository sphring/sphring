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


namespace Arthurh\Sphring\Extend;


use Arthurh\Sphring\AbstractTestSphring;
use Arthurh\Sphring\Exception\SphringException;
use Arthurh\Sphring\Extender\Extender;
use Arthurh\Sphring\Sphring;

class ExtendTest extends AbstractTestSphring
{
    const SIMPLE_TEST_FILE = 'mainSimpleTest.yml';
    const ONEXIST_TEST_FILE = 'mainSimpleTestOnExist.yml';

    public function testExtendSimpleValid()
    {
        $sphring = Sphring::getInstance();
        $sphring->clear();
        Extender::$DEFAULT_FILENAME = 'sphring-extend-simple.yml';
        $sphring->loadContext(self::$CONTEXT_EXTEND_FOLDER . '/' . self::SIMPLE_TEST_FILE);
        $useBean = $sphring->getBean('usebean');
        $this->getLogger()->debug('test extend valid ' . print_r($useBean, true));
        $this->assertArrayHasKey('testExtend', $useBean->getJuju());
    }

    public function testExtendOnExistValid()
    {
        $sphring = Sphring::getInstance();
        $sphring->clear();
        Extender::$DEFAULT_FILENAME = 'sphring-extend-onexist.yml';
        $sphring->loadContext(self::$CONTEXT_EXTEND_FOLDER . '/' . self::ONEXIST_TEST_FILE);
        $useBean = $sphring->getBean('usebean');
        $this->getLogger()->debug('test extend valid ' . print_r($useBean, true));
        $this->assertArrayHasKey('testExtend', $useBean->getJuju());
    }
} 