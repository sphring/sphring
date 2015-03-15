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
        if ($file == null) {
            throw new BeanPropertyException("Error when injecting xml in bean, file '%s' doesn't exist.", $file);
        }
        return $this->loadXml($file, $asArray);
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
            $data = simplexml_load_file($file, null, LIBXML_NOERROR);
        } else {
            $data = simplexml_load_file($file);
        }

        if ($data === false) {
            $errors = libxml_get_errors();
            $latestError = array_pop($errors);
            $error = array(
                'message' => $latestError->message,
                'type' => $latestError->level,
                'code' => $latestError->code,
                'file' => $latestError->file,
                'line' => $latestError->line,
            );
            throw new SphringException("Error when injecting xml in bean: ", $this->getExceptionFromSimpleXml($error));
        }
        if ($asArray) {
            return json_decode(json_encode($data), true);
        } else {
            return $data;
        }
    }

    private function getExceptionFromSimpleXml(array $error)
    {
        $message = $error['message'];
        $code = isset($error['code']) ? $error['code'] : 0;
        $severity = isset($error['type']) ? $error['type'] : 1;
        $filename = isset($error['file']) ? $error['file'] : __FILE__;
        $lineno = isset($error['line']) ? $error['line'] : __LINE__;
        $exception = isset($error['exception']) ? $error['exception'] : null;
        return new \Exception($message, $code, $severity, $filename, $lineno, $exception);
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
