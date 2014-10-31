<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 31/10/2014
 */


namespace Arthurh\Sphring\FakeExtend;


use Arthurh\Sphring\Model\SphringGlobal;

class LoadContextSphringGlobal extends SphringGlobal
{
    public function run()
    {
        $this->getSphring()->jojo = "test";
    }


}