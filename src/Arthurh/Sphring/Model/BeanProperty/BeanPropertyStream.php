<?php
/**
 * Copyright (C) 2014 Orange
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 14/10/2014
 */


namespace Arthurh\Sphring\Model\BeanProperty;


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
        $context = self::getContext($datas['context']);
        if (empty($context)) {
            return file_get_contents($resource);
        }
        return file_get_contents($resource, 0, $context);
    }

    /**
     * @param null $context
     * @return null|resource
     */
    public static function getContext($context = null)
    {
        $proxy = self::getProxy();
        if (empty($proxy)) {
            return empty($context) ? null : stream_context_create($context);
        }
        $proxyContext = array(
            'proxy' => $proxy,
            'request_fulluri' => true
        );
        if (empty($context)) {
            $context['http'] = $proxyContext;
        } else {
            $context['http'] = array_merge($proxyContext, $context['http']);
        }
        return stream_context_create($context);
    }

    /**
     * @return null|string
     */
    public static function getProxy()
    {
        $proxyKeys = array("HTTP_PROXY", "HTTPS_PROXY", "http_proxy", "https_proxy");
        $proxyUri = null;
        foreach ($proxyKeys as $proxyKey) {
            $proxy = getenv($proxyKey);
            if (!empty($proxy)) {
                $proxyUri = $proxy;
                break;
            }
            $proxy = $_SERVER[$proxyKey];
            if (!empty($proxy)) {
                $proxyUri = $proxy;
                break;
            }
        }
        $proxyUri = str_replace('http', 'tcp', $proxyUri);
        return $proxyUri;
    }
}