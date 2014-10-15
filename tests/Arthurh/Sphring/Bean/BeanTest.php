<?php
namespace Arthurh\Sphring\Bean;

use Arthurh\Sphring\AbstractTestSphring;
use Arthurh\Sphring\Sphring;

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
class BeanTest extends AbstractTestSphring
{
    /**
     * @var \Arthurh\Sphring\Model\Bean
     */
    private $testBean;

    /**
     * @before
     */
    public function instanciation()
    {
        $this->testBean = new \Arthurh\Sphring\Model\Bean('testBean');
        $sphring = new Sphring('');
        $this->testBean->setSphringEventDispatcher($sphring->getSphringEventDispatcher());
        $_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../zorro.php';
    }

    public function testInjectionValue()
    {
        $datas = array('koi' => 'quoi');
        $this->testBean->setTest(
            array('value' =>
                $datas
            ));
        $this->assertEquals(json_encode($datas), json_encode($this->testBean->getTest()->getInjection()));
    }

    public function testInjectionAbsoluteYml()
    {
        $this->testBean->setTest(
            array('yml' =>
                self::$RESOURCE_FOLDER . '/testyml.yml'
            ));
        $this->assertArrayHasKey('invoice', $this->testBean->getTest()->getInjection());
    }

    public function testInjectionRootProjectYml()
    {
        $this->testBean->setTest(
            array('yml' =>
                '/Resources/testyml.yml'
            ));
        $this->assertArrayHasKey('invoice', $this->testBean->getTest()->getInjection());
    }

    public function testInjectionAbsoluteIni()
    {
        $this->testBean->setTest(
            array('iniFile' =>
                self::$RESOURCE_FOLDER . '/testini.ini'
            ));
        $this->assertEquals('db.example.com', $this->testBean->getTest()->getInjection()->production->database->params->host);
    }

    public function testInjectionAbsoluteIniWithEnv()
    {
        $this->testBean->setTest(
            array('iniFile' =>
                array('production' => self::$RESOURCE_FOLDER . '/testini.ini')
            ));
        $this->assertEquals('db.example.com', $this->testBean->getTest()->getInjection()->database->params->host);
    }

    public function testInjectionRootProjectIni()
    {

        $this->testBean->setTest(
            array('iniFile' =>
                '/Resources/testini.ini'
            ));
        $this->assertEquals('db.example.com', $this->testBean->getTest()->getInjection()->production->database->params->host);
    }

    public function testInjectionStream()
    {
        $this->testBean->setTest(
            array('stream' =>
                array('resource' => 'http://php.net/')
            ));
        $this->assertEquals(file_get_contents('http://php.net/', false, \Arthurh\Sphring\Model\BeanProperty\BeanPropertyStream::getContext()), $this->testBean->getTest()->getInjection());
    }

    public function testInjectionStreamWithContext()
    {
        $this->testBean->setTest(
            array('stream' =>
                array(
                    'resource' => 'http://php.net/',
                    'context' => array('http' => array('method' => 'GET'))
                )
            ));
        $this->assertEquals(file_get_contents('http://php.net/', false, \Arthurh\Sphring\Model\BeanProperty\BeanPropertyStream::getContext()), $this->testBean->getTest()->getInjection());
    }

    /**
     * @expectedException \Arthurh\Sphring\Exception\BeanException
     */
    public function testNotValidPropertyException()
    {
        $this->testBean->setTest(
            array('notvalid' => 'zorro')
        );
    }

    /**
     * @expectedException \Arthurh\Sphring\Exception\BeanException
     */
    public function testNotValidMethodException()
    {
        $this->testBean->notValid(
            array('notvalid' => 'zorro')
        );
    }
} 