<?php
/**
 * Copyright (C) 2015 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 21/05/2015
 */


namespace Arthurh\Sphring\FakeBean;


class FooFactory
{
    public function createFoo($name)
    {
        return new Foo($name);
    }
}