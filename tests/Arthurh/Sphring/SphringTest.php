<?php
namespace Arthurh\Sphring;

use Arhframe\Util\Proxy;
use Arthurh\Sphring\Exception\SphringException;
use Arthurh\Sphring\FakeBean\Foo;
use Arthurh\Sphring\FakeBean\IFoo;
use Arthurh\Sphring\FakeBean\IUsing;
use Arthurh\Sphring\Logger\LoggerSphring;
use Arthurh\Sphring\Model\Bean\Bean;
use Arthurh\Sphring\Model\Bean\ProxyBean;

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
class SphringTest extends AbstractTestSphring
{
    const SIMPLE_TEST_FILE = 'mainSimpleTest.yml';
    const SIMPLE_TEST_CONSTRUCTOR_FILE = 'mainSimpleConstructorTest.yml';
    const METHOD_INIT_TEST_FILE = 'mainMethodInitTest.yml';
    const ASSOCREF_TEST_FILE = 'mainAssocRefTest.yml';
    const ABSTRACT_TEST_FILE = 'mainTestAbstractBean.yml';
    const IMPORT_TEST_FILE = 'testimport/main.yml';

    const YML_TEST_FILE = 'mainTestYml.yml';
    const INI_TEST_FILE = 'mainTestIni.yml';
    const STREAM_TEST_FILE = 'mainTestStream.yml';
    const TEST_BEAN_ID = 'testBean';

    public function testSimple()
    {
        $sphring = new Sphring(self::$CONTEXT_FOLDER . '/' . self::SIMPLE_TEST_FILE);
        $sphring->setLogger(LoggerSphring::getInstance()->getLogger());
        $sphring->setSphringEventDispatcher($sphring->getSphringEventDispatcher());
        $sphring->setExtender($sphring->getExtender());
        $sphring->loadContext();
        $useBean = $sphring->getBean('usebean');
        $this->assertTrue($useBean->__getBean()->getObject() instanceof IUsing);
        $this->assertTrue($useBean->getFoo() instanceof IFoo);
        $this->assertEquals(realpath(self::$CONTEXT_FOLDER . '/' . self::SIMPLE_TEST_FILE), $sphring->getFilename());
    }

    public function testSimpleConstructor()
    {
        $sphring = new Sphring(self::$CONTEXT_FOLDER . '/' . self::SIMPLE_TEST_CONSTRUCTOR_FILE);
        $sphring->setLogger(LoggerSphring::getInstance()->getLogger());
        $sphring->setSphringEventDispatcher($sphring->getSphringEventDispatcher());
        $sphring->setExtender($sphring->getExtender());
        $sphring->loadContext();
        $foobean = $sphring->getBean('foobean');
        $this->assertEquals('testkiki', $foobean->getKiki());
    }

    public function testMethodInit()
    {
        $sphring = new Sphring(self::$CONTEXT_FOLDER . '/' . self::METHOD_INIT_TEST_FILE);
        $sphring->setLogger(LoggerSphring::getInstance()->getLogger());
        $sphring->setSphringEventDispatcher($sphring->getSphringEventDispatcher());
        $sphring->setExtender($sphring->getExtender());
        $sphring->loadContext();
        $foobean = $sphring->getBean('foobean');
        $this->assertEquals('initValue', $foobean->getInitValue());
    }

    public function testAssocRef()
    {
        $sphring = new Sphring(self::$CONTEXT_FOLDER . '/' . self::ASSOCREF_TEST_FILE);
        $sphring->loadContext();
        $foo = $sphring->getBean('foobean');
        var_dump($foo->getKiki());
        $this->assertCount(2, $foo->getKiki());
        $this->assertArrayHasKey('usebean1', $foo->getKiki());
    }

    public function testArrayRef()
    {
        $sphring = new Sphring(self::$CONTEXT_FOLDER . '/' . self::ASSOCREF_TEST_FILE);
        $sphring->loadContext();
        $foo = $sphring->getBean('foobean');
        var_dump($foo->getKiki());
        $this->assertCount(2, $foo->getCucu());
        $this->assertArrayNotHasKey('usebean1', $foo->getCucu());
    }

    public function testAbstract()
    {
        $sphring = new Sphring(self::$CONTEXT_FOLDER . '/' . self::ABSTRACT_TEST_FILE);
        $sphring->loadContext();
        $useBean = $sphring->getBean('usebean');
        $this->assertTrue($useBean->__getBean()->getObject() instanceof IUsing);
        $this->assertTrue($useBean->getFoo() instanceof IFoo);
    }

    public function testImport()
    {
        $sphring = new Sphring(self::$CONTEXT_FOLDER . '/' . self::IMPORT_TEST_FILE);
        $sphring->loadContext();
        $useBean = $sphring->getBean('usebean');
        $this->assertTrue($useBean->__getBean()->getObject() instanceof IUsing);
        $this->assertTrue($useBean->getFoo() instanceof IFoo);
    }

    public function testYml()
    {
        $sphring = new Sphring(self::$CONTEXT_FOLDER . '/' . self::YML_TEST_FILE);
        $sphring->loadContext();
        $useBean = $sphring->getBean('usebean');
        $this->assertArrayHasKey('invoice', $useBean->getJojo());

    }

    public function testIni()
    {
        $sphring = new Sphring(self::$CONTEXT_FOLDER . '/' . self::INI_TEST_FILE);
        $sphring->clear();
        $sphring->loadContext();
        $useBean = $sphring->getBean('usebean');
        $this->assertEquals('db.example.com', $useBean->getJojo()->production->database->params->host);

    }

    public function testStream()
    {
        $sphring = new Sphring(self::$CONTEXT_FOLDER . '/' . self::STREAM_TEST_FILE);
        $sphring->loadContext();
        $useBean = $sphring->getBean('usebean');
        $this->assertEquals(file_get_contents('http://php.net/', false, Proxy::createStreamContext()), $useBean->getJojo());

    }

    public function testRemoveBean()
    {
        $sphring = new Sphring(self::$CONTEXT_FOLDER . '/' . self::SIMPLE_TEST_FILE);
        $sphring->loadContext();
        $beanId = self::TEST_BEAN_ID;
        $bean = new Bean($beanId);
        $bean->setSphringEventDispatcher($sphring->getSphringEventDispatcher());
        $bean->setClass('Arthurh\\Sphring\\FakeBean\\Foo');
        $sphring->addBean(new ProxyBean($bean));
        $this->assertTrue($sphring->getBean($beanId)->__getBean()->getObject() instanceof Foo);

        $sphring->removeBean(self::TEST_BEAN_ID);
        try {
            $sphring->getBean(self::TEST_BEAN_ID);
            $this->assertTrue(false);
        } catch (SphringException $e) {
            $this->assertTrue(true);
        }


        $sphring->addBean(new ProxyBean($bean));
        $this->assertTrue($sphring->getBean($beanId)->__getBean()->getObject() instanceof Foo);
        $sphring->removeBean($bean);
        try {
            $sphring->getBean($bean->getId());
            $this->assertTrue(false);
        } catch (SphringException $e) {
            $this->assertTrue(true);
        }

    }

    public function testAddBean()
    {
        $sphring = new Sphring(self::$CONTEXT_FOLDER . '/' . self::SIMPLE_TEST_FILE);
        $sphring->loadContext();
        $beanId = self::TEST_BEAN_ID;
        $bean = new Bean($beanId);
        $bean->setSphringEventDispatcher($sphring->getSphringEventDispatcher());
        $bean->setClass('Arthurh\\Sphring\\FakeBean\\Foo');
        $sphring->addBean(new ProxyBean($bean));
        $this->assertTrue($sphring->getBean($beanId)->__getBean()->getObject() instanceof Foo);
    }
} 