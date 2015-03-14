<?php
namespace Arthurh\Sphring\Bean;

use Arhframe\Util\File;
use Arhframe\Util\Folder;
use Arthurh\Sphring\AbstractTestSphring;
use Arthurh\Sphring\Sphring;

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
class BeanTest extends AbstractTestSphring
{
    /**
     * @var \Arthurh\Sphring\Model\Bean\Bean
     */
    private $testBean;


    /**
     * @before
     */
    public function instanciation()
    {
        $_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../zorro.php';
        $this->testBean = new \Arthurh\Sphring\Model\Bean\Bean('testBean');
        $sphring = new Sphring('');
        $sphring->beforeLoad();
        $this->testBean->setSphringEventDispatcher($sphring->getSphringEventDispatcher());


    }

    public function testInjectionValue()
    {
        $datas = array('koi' => 'quoi');
        $this->testBean->addProperty('test',
            array('value' =>
                $datas
            ));
        $this->assertEquals(json_encode($datas), json_encode($this->testBean->getProperty('test')->getInjection()));
    }

    public function testInjectionAbsoluteYml()
    {
        $this->testBean->addProperty('test',
            array('yml' =>
                self::$RESOURCE_FOLDER . '/testyml.yml'
            ));
        $this->assertArrayHasKey('invoice', $this->testBean->getProperty('test')->getInjection());
    }

    public function testInjectionRootProjectYml()
    {
        $this->testBean->addProperty('test',
            array('yml' =>
                '/Resources/testyml.yml'
            ));
        $this->assertArrayHasKey('invoice', $this->testBean->getProperty('test')->getInjection());
    }

    public function testInjectionAbsoluteIni()
    {
        $this->testBean->addProperty('test',
            array('iniFile' =>
                self::$RESOURCE_FOLDER . '/testini.ini'
            ));
        $this->assertEquals('db.example.com', $this->testBean->getProperty('test')->getInjection()->production->database->params->host);
    }

    public function testInjectionFile()
    {
        $this->testBean->addProperty('test',
            array('file' =>
                self::$RESOURCE_FOLDER . '/testini.ini'
            ));
        $this->assertInstanceOf(File::class, $this->testBean->getProperty('test')->getInjection());
    }

    public function testInjectionFolder()
    {
        $this->testBean->addProperty('test',
            array('folder' =>
                self::$RESOURCE_FOLDER
            ));
        $this->assertInstanceOf(Folder::class, $this->testBean->getProperty('test')->getInjection());
    }

    public function testInjectionAbsoluteIniWithEnv()
    {
        $this->testBean->addProperty('test',
            array('iniFile' =>
                array('production' => self::$RESOURCE_FOLDER . '/testini.ini')
            ));
        $this->assertEquals('db.example.com', $this->testBean->getProperty('test')->getInjection()->database->params->host);
    }

    public function testInjectionRootProjectIni()
    {

        $this->testBean->addProperty('test',
            array('iniFile' =>
                '/Resources/testini.ini'
            ));
        $this->assertEquals('db.example.com', $this->testBean->getProperty('test')->getInjection()->production->database->params->host);
    }

    public function testInjectionStream()
    {
        $this->testBean->addProperty('test',
            array('stream' =>
                array('resource' => 'http://php.net/')
            ));
        $this->assertEquals(file_get_contents('http://php.net/', false, \Arthurh\Sphring\Model\BeanProperty\BeanPropertyStream::getContext()), $this->testBean->getProperty('test')->getInjection());
    }


    public function testInjectionStreamWithFakeProxy()
    {
        $_SERVER['HTTP_PROXY'] = "http://fakeproxy";
        $this->testBean->addProperty('test',
            array('stream' =>
                array('resource' => 'http://php.net/')
            ));

        @file_get_contents('http://php.net/', false, \Arthurh\Sphring\Model\BeanProperty\BeanPropertyStream::getContext());
        unset($_SERVER['HTTP_PROXY']);
    }

    public function testInjectionStreamWithContext()
    {
        $this->testBean->addProperty('test',
            array('stream' =>
                array(
                    'resource' => 'http://php.net/',
                    'context' => array('http' => array('method' => 'GET'))
                )
            ));
        $this->assertEquals(file_get_contents('http://php.net/', false, \Arthurh\Sphring\Model\BeanProperty\BeanPropertyStream::getContext()), $this->testBean->getProperty('test')->getInjection());
    }

    /**
     * @expectedException \Arthurh\Sphring\Exception\BeanException
     */
    public function testNotValidPropertyException()
    {
        $this->testBean->addProperty('test',
            array('notvalid' => 'zorro')
        );
    }

    /**
     * @expectedException \Arthurh\Sphring\Exception\BeanException
     */
    public function testNotValidMethodException()
    {
        $this->testBean->addProperty('notValid',
            array('notvalid' => 'zorro')
        );
    }
} 