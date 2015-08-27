<?php
/**
 * Copyright (C) 2015 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 09/06/2015
 */


namespace Arthurh\Sphring\SpringContextConverter;


use Arthurh\Sphring\AbstractTestSphring;
use Arthurh\Sphring\Sphring;

class SpringContextConverterTest extends AbstractTestSphring
{
    public function testSimple()
    {
        $converter = new SpringContextConverter(new Sphring());
        $converter->convertToSphring('<?xml version="1.0" encoding="UTF-8"?>
<beans xmlns="http://www.springframework.org/schema/beans"
       xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
       xsi:schemaLocation="http://www.springframework.org/schema/beans
	   					   http://www.springframework.org/schema/beans/spring-beans.xsd">

    <bean id="message"
          class="org.springbyexample.di.app.Message">
        <property name="message" value="Spring is fun." />
    </bean>

</beans>');
    }
}