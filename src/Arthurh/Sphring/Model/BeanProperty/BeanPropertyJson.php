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


use Arhframe\Util\File;
use Arthurh\Sphring\Exception\BeanPropertyException;

class BeanPropertyJson extends AbstractBeanPropertyFileLoader
{

    /**
     * @return mixed
     * @throws BeanPropertyException
     * @throws \Arhframe\Util\UtilException
     */
    public function inject()
    {
        $data = $this->getData();
        $asArray = true;
        if (is_array($data)) {
            $file = $data['file'];
            $asArray = empty($data['asObject']) ? true : false;
        } else {
            $file = $data;
        }
        $file = $this->getFilePath($file);
        if ($file === null) {
            throw new BeanPropertyException("Error when injecting json in bean, file '%s' doesn't exist.", $file);
        }
        $file = new File($file);
        return json_decode($file->getContent(), $asArray);
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
                        'asObject' => ['_type' => 'boolean']
                    ]
                ],
                [
                    '_type' => 'text'
                ]
            ]
        ];
    }
}
