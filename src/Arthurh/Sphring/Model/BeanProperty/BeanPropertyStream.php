<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 14/10/2014
 */

namespace Arthurh\Sphring\Model\BeanProperty;

use Arhframe\Util\Proxy;

/**
 * Class BeanPropertyStream
 * @package Arthurh\Sphring\Model\BeanProperty
 */
class BeanPropertyStream extends AbstractBeanProperty
{

    /**
     * @return string
     */
    public function inject()
    {
        $datas = $this->getData();
        $resource = $datas['resource'];
        $context = Proxy::createStreamContext($datas['context']);
        if (empty($context)) {
            return file_get_contents($resource);
        }
        return file_get_contents($resource, false, $context);
    }

    /**
     * @return array
     */
    public static function getValidation()
    {
        return [
            '_type' => 'array',
            '_ignore_extra_keys' => true,
            '_children' => [
                'resource' => [
                    '_type' => 'text',
                    '_required' => true
                ]
            ]
        ];
    }
}
