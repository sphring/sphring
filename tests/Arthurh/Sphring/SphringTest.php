<?php
namespace Arthurh\Sphring;

use Arthurh\Sphring\Exception\SphringException;
use Arthurh\Sphring\FakeBean\Foo;
use Arthurh\Sphring\FakeBean\IFoo;
use Arthurh\Sphring\FakeBean\IUsing;
use Arthurh\Sphring\Model\Bean;

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
class SphringTest extends AbstractTestSphring
{
    const SIMPLE_TEST_FILE = 'mainSimpleTest.yml';
    const ABSTRACT_TEST_FILE = 'mainTestAbstractBean.yml';
    const IMPORT_TEST_FILE = 'testimport/main.yml';
    const YML_TEST_FILE = 'mainTestYml.yml';
    const INI_TEST_FILE = 'mainTestIni.yml';
    const STREAM_TEST_FILE = 'mainTestStream.yml';
    const TEST_BEAN_ID = 'testBean';

    public function testSimple()
    {
        $sphring = Sphring::getInstance();
        try {
            $sphring->getBean('usebean');
        } catch (SphringException $e) {
            $this->assertTrue(true);
        }
        $sphring->loadContext(self::$CONTEXT_FOLDER . '/' . self::SIMPLE_TEST_FILE);
        $useBean = $sphring->getBean('usebean');
        $this->getLogger()->debug('test simple ' . print_r($useBean, true));
        $this->assertTrue($useBean instanceof IUsing);
        $this->assertTrue($useBean->getFoo() instanceof IFoo);
    }

    public function testAbstract()
    {
        $sphring = Sphring::getInstance();
        try {
            $sphring->getBean('usebean');
        } catch (SphringException $e) {
            $this->assertTrue(true);
        }
        $sphring->loadContext(self::$CONTEXT_FOLDER . '/' . self::ABSTRACT_TEST_FILE);
        $useBean = $sphring->getBean('usebean');
        $this->getLogger()->debug('test abstract ' . print_r($useBean, true));
        $this->assertTrue($useBean instanceof IUsing);
        $this->assertTrue($useBean->getFoo() instanceof IFoo);
    }

    public function testImport()
    {
        $sphring = Sphring::getInstance();
        try {
            $sphring->getBean('usebean');
        } catch (SphringException $e) {
            $this->assertTrue(true);
        }
        $sphring->loadContext(self::$CONTEXT_FOLDER . '/' . self::IMPORT_TEST_FILE);
        $useBean = $sphring->getBean('usebean');
        $this->getLogger()->debug('test import ' . print_r($useBean, true));
        $this->assertTrue($useBean instanceof IUsing);
        $this->assertTrue($useBean->getFoo() instanceof IFoo);
    }

    public function testYml()
    {
        $sphring = Sphring::getInstance();
        try {
            $sphring->getBean('usebean');
        } catch (SphringException $e) {
            $this->assertTrue(true);
        }
        $sphring->loadContext(self::$CONTEXT_FOLDER . '/' . self::YML_TEST_FILE);
        $useBean = $sphring->getBean('usebean');
        $this->getLogger()->debug('test yml ' . print_r($useBean, true));
        $this->assertArrayHasKey('invoice', $useBean->getJojo());

    }

    public function testIni()
    {
        $sphring = Sphring::getInstance();
        try {
            $sphring->getBean('usebean');
        } catch (SphringException $e) {
            $this->assertTrue(true);
        }
        $sphring->loadContext(self::$CONTEXT_FOLDER . '/' . self::INI_TEST_FILE);
        $useBean = $sphring->getBean('usebean');
        $this->getLogger()->debug('test ini ' . print_r($useBean, true));
        $this->assertEquals('db.example.com', $useBean->getJojo()->production->database->params->host);

    }

    public function testStream()
    {
        $sphring = Sphring::getInstance();
        try {
            $sphring->getBean('usebean');
        } catch (SphringException $e) {
            $this->assertTrue(true);
        }
        $sphring->loadContext(self::$CONTEXT_FOLDER . '/' . self::STREAM_TEST_FILE);
        $useBean = $sphring->getBean('usebean');
        $this->getLogger()->debug('test stream ' . print_r($useBean, true));
        $this->assertEquals(file_get_contents('http://php.net/', false, \Arthurh\Sphring\Model\BeanProperty\BeanPropertyStream::getContext()), $useBean->getJojo());

    }

    public function testAddBean()
    {
        $sphring = Sphring::getInstance();
        try {
            $sphring->getBean('usebean');
        } catch (SphringException $e) {
            $this->assertTrue(true);
        }
        $sphring->loadContext(self::$CONTEXT_FOLDER . '/' . self::SIMPLE_TEST_FILE);
        $beanId = self::TEST_BEAN_ID;
        $bean = new Bean($beanId);
        $bean->setClass('Arthurh\\Sphring\\FakeBean\\Foo');
        $sphring->addBean($bean);
        $this->assertTrue($sphring->getBean($beanId) instanceof Foo);
    }

    public function testRemoveBean()
    {
        $this->testAddBean();
        $sphring = Sphring::getInstance();
        $sphring->removeBean(self::TEST_BEAN_ID);
        try {
            $sphring->getBean(self::TEST_BEAN_ID);
        } catch (SphringException $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }
} 