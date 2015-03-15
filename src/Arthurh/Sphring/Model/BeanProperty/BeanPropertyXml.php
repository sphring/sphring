<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 15/03/2015
 */

namespace Arthurh\Sphring\Model\BeanProperty;


use Arthurh\Sphring\Exception\BeanPropertyException;
use Arthurh\Sphring\Exception\SphringException;

class BeanPropertyXml extends AbstractBeanPropertyFileLoader
{

    /**
     * @return mixed
     * @throws BeanPropertyException
     * @throws SphringException
     */
    public function inject()
    {
        $data = $this->getData();
        $asArray = false;
        if (is_array($data)) {
            $file = $data['file'];
            $asArray = empty($data['asArray']) ? false : true;
        } else {
            $file = $data;
        }
        $file = $this->getFilePath($file);
        if ($file === null) {
            throw new BeanPropertyException("Error when injecting xml in bean, file '%s' doesn't exist.", $file);
        }
        try {
            $data = $this->loadXml($file, $asArray);
            return $data;
        } catch (\Exception $e) {
            throw new SphringException("Error when injecting xml in bean: ", $e);
        }
    }

    /**
     * @param $file
     * @param bool $asArray
     * @return mixed|\SimpleXMLElement
     * @throws SphringException
     */
    private function loadXml($file, $asArray = false)
    {
        libxml_use_internal_errors(true);
        if ($asArray) {
            return $this->loadXmlArray($file);
        } else {
            return $this->loadXmlObject($file);
        }
    }

    /**
     * @param $file
     * @return \SimpleXMLElement
     */
    private function loadXmlObject($file)
    {
        return simplexml_load_file($file);
    }

    /**
     * @param $file
     * @return mixed
     * @throws \Exception
     */
    private function loadXmlArray($file)
    {
        $data = simplexml_load_file($file, null, LIBXML_NOERROR);
        if ($data === false) {
            $errors = libxml_get_errors();
            $latestError = array_pop($errors);
            throw new \Exception($latestError->message, $latestError->code);
        }
        return json_decode(json_encode($data), true);
    }

    /**
     * @return array
     */
    public static function getValidation()
    {
        return [
            '_type' => 'choice',
            '_choices' => [
                [
                    '_type' => 'array',
                    '_children' => [
                        'file' => [
                            '_type' => 'text',
                            '_required' => true
                        ],
                        'asArray' => ['_type' => 'boolean']
                    ]
                ],
                [
                    '_type' => 'text'
                ]
            ]
        ];
    }
}
