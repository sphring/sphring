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


use Arthurh\Sphring\Sphring;
use Arthurh\Sphring\SpringContextConverter\Converters\BeanConverter;
use Arthurh\Sphring\SpringContextConverter\Converters\BeansConverter;
use Arthurh\Sphring\SpringContextConverter\Converters\Converter;
use Sabre\Xml\Reader;

class SpringContextConverter implements Converter
{
    /**
     * @var Sphring
     */
    private $sphring;

    /**
     * @var Converter[]
     */
    private $converters = [];

    /**
     * SpringContextConverter constructor.
     * @param Sphring $sphring
     */
    public function __construct(Sphring $sphring)
    {
        $this->sphring = $sphring;
        $this->init();
    }

    public function init()
    {
        $this->converters['{http://www.springframework.org/schema/beans}beans'] = new BeansConverter();
        $this->converters['{http://www.springframework.org/schema/beans}bean'] = new BeanConverter();
    }

    /**
     * @return Sphring
     */
    public function getSphring()
    {
        return $this->sphring;
    }

    /**
     * @param Sphring $sphring
     */
    public function setSphring($sphring)
    {
        $this->sphring = $sphring;
    }


    public function convertToSphring($springXml)
    {
        $reader = new Reader();
        $reader->xml($springXml);
        $source = [];
        print_r($reader->parse());
        $this->convert([$reader->parse()], $source);
        print_r($source);
    }

    function convert(array $arrayXml, array &$source)
    {
        foreach ($arrayXml as $data) {
            if (isset($this->converters[$data['name']])) {
                $outputSource = $this->converters[$data['name']]->convert($data, $source);
            }
            if (is_array($data['value'])) {
                $this->convert($data['value'], $outputSource);
            }
        }

    }
}
