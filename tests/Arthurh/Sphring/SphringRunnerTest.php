<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 18/10/2014
 */

namespace Arthurh\Sphring;


use Arthurh\Sphring\FakeBean\Foo;
use Arthurh\Sphring\FakeBean\IFoo;
use Arthurh\Sphring\FakeBean\IUsing;
use Arthurh\Sphring\FakeRunner\FakeSphringRunner;

class SphringRunnerTest extends AbstractTestSphring
{
    public function testLoadRunner()
    {
        $sphringRunner = FakeSphringRunner::getInstance(__DIR__ . '/../Resources/composer/composer.lock');
        $useBean = $sphringRunner->getBean('usebean');
        $this->assertTrue($useBean->__getBean()->getObject() instanceof IUsing);
        $this->assertTrue($useBean->getFoo() instanceof IFoo);
    }

    public function testBeforeLoad()
    {
        $sphringRunner = FakeSphringRunner::getInstance();
        $this->assertTrue($sphringRunner->getIsBeforeLoad());
    }

    public function testBeforeStart()
    {
        $sphringRunner = FakeSphringRunner::getInstance();
        $this->assertTrue($sphringRunner->getIsBeforeStart());
    }

    public function testAfterLoad()
    {
        $sphringRunner = FakeSphringRunner::getInstance();
        $this->assertTrue($sphringRunner->getIsAfterLoad());
    }

    public function testAutowire()
    {
        $sphringRunner = FakeSphringRunner::getInstance();
        $this->assertInstanceOf(Foo::class, $sphringRunner->getFoo());
    }
}
 