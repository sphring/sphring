<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 15/10/2014
 */

namespace Arthurh\Sphring;


use Arthurh\Sphring\EventDispatcher\Listener\BeanPropertyListener;

class SphringBoot
{
    public static function boot()
    {
        self::bootBeanProperty();
    }

    public static function bootBeanProperty()
    {
        BeanPropertyListener::getInstance()->register('iniFile', "Arthurh\\Sphring\\Model\\BeanProperty\\BeanPropertyIniFile");
        BeanPropertyListener::getInstance()->register('ref', "Arthurh\\Sphring\\Model\\BeanProperty\\BeanPropertyRef");
        BeanPropertyListener::getInstance()->register('stream', "Arthurh\\Sphring\\Model\\BeanProperty\\BeanPropertyStream");
        BeanPropertyListener::getInstance()->register('value', "Arthurh\\Sphring\\Model\\BeanProperty\\BeanPropertyValue");
        BeanPropertyListener::getInstance()->register('yml', "Arthurh\\Sphring\\Model\\BeanProperty\\BeanPropertyYml");
    }
} 