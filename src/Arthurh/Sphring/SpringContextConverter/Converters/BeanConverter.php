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


namespace Arthurh\Sphring\SpringContextConverter\Converters;


class BeanConverter implements Converter
{

    function convert(array $arrayXml, array &$source)
    {
        $beanId = $arrayXml['attributes']['id'];
        $source[$beanId] = [];
        if (isset($arrayXml['attributes']['class'])) {
            $source[$beanId]['class'] = str_replace('.', '\\', $arrayXml['attributes']['class']);
        }
        if (isset($arrayXml['attributes']['abstract']) && strtolower($arrayXml['attributes']['abstract']) === 'true') {
            $source[$beanId]['type'] = 'abstract';
        }
        if (isset($arrayXml['attributes']['parent'])) {
            $source['extend'] = $arrayXml['attributes']['parent'];
        }
        $source[$beanId]['properties'] = [];
        return $source[$beanId];
    }
}
