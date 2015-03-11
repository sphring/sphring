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

namespace Arthurh\Sphring\FakeRunner;

use Arthurh\Sphring\FakeBean\Foo;
use Arthurh\Sphring\Runner\SphringRunner;


/**
 * Class SphringRunnerTest
 *
 * @LoadContext(tests/Arthurh/Sphring/Resources/sphring/mainSimpleTest.yml)
 * @RootProject(../../../../)
 */
class FakeSphringRunner extends SphringRunner
{
    /**
     * @var bool
     */
    private $isBeforeLoad = false;
    /**
     * @var bool
     */
    private $isBeforeStart = false;
    /**
     * @var bool
     */
    private $isAfterLoad = false;
    /**
     * @var Foo
     */
    private $foo;

    /**
     * @BeforeLoad
     */
    public function beforeLoad()
    {
        $this->isBeforeLoad = true;
    }

    /**
     * @BeforeStart
     */
    public function beforeStart()
    {
        $this->isBeforeStart = true;
    }

    /**
     * @AfterLoad
     */
    public function afterLoad()
    {
        $this->isAfterLoad = true;
    }

    /**
     * @return boolean
     */
    public function getIsAfterLoad()
    {
        return $this->isAfterLoad;
    }

    /**
     * @param boolean $isAfterLoad
     */
    public function setIsAfterLoad($isAfterLoad)
    {
        $this->isAfterLoad = $isAfterLoad;
    }

    /**
     * @return boolean
     */
    public function getIsBeforeLoad()
    {
        return $this->isBeforeLoad;
    }

    /**
     * @param boolean $isBeforeLoad
     */
    public function setIsBeforeLoad($isBeforeLoad)
    {
        $this->isBeforeLoad = $isBeforeLoad;
    }

    /**
     * @return boolean
     */
    public function getIsBeforeStart()
    {
        return $this->isBeforeStart;
    }

    /**
     * @param boolean $isBeforeStart
     */
    public function setIsBeforeStart($isBeforeStart)
    {
        $this->isBeforeStart = $isBeforeStart;
    }

    /**
     * @return Foo
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param Foo $foo
     * @AutoWire
     */
    public function setFoo(Foo $foo)
    {
        $this->foo = $foo;
    }

}
 