<?php
/**
 * Copyright (C) 2015 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 31/03/2015
 */


namespace Arthurh\Sphring\ProxyGenerator;


use Arthurh\Sphring\EventDispatcher\AnnotationsDispatcher;
use Arthurh\Sphring\Model\Bean\AbstractBean;
use ProxyManager\Factory\AccessInterceptorValueHolderFactory;

class ProxyGenerator
{
    /**
     * @var ProxyGenerator
     */
    private static $_instance = null;

    /**
     * @var AccessInterceptorValueHolderFactory
     */
    private $proxyFactory;

    private function __construct()
    {
        $this->setProxyFactory(new AccessInterceptorValueHolderFactory());
    }

    /**
     * Return the ProxyGenerator
     * @return ProxyGenerator
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new ProxyGenerator();
        }

        return self::$_instance;
    }

    public function proxyFromBean(AbstractBean $bean)
    {
        $object = $bean->getObject();
        if ($object === null) {
            return null;
        }
        $rc = new \ReflectionClass($object);

        $methods = $rc->getMethods();
        $beforeCall = $this->getBeforeCallMethod($methods, $bean);
        $afterCall = $this->getAfterCallMethod($methods, $bean);
        $proxyObject = $this->proxyFactory->createProxy(
            $object,
            $beforeCall,
            $afterCall
        );
        return $proxyObject;
    }

    /**
     * @param \ReflectionMethod[] $methods
     * @param AbstractBean $bean
     * @return array
     */
    private function getBeforeCallMethod($methods, AbstractBean $bean)
    {
        $beforeCall = [];
        foreach ($methods as $method) {

            $methodName = $method->getName();

            $beforeCall[$methodName] = function ($proxy, $instance, $method, $params, & $returnEarly) use ($methodName, $bean) {

                $annotationDispatcher = new AnnotationsDispatcher($bean, $bean->getClass(), $bean->getSphringEventDispatcher());
                $annotationDispatcher->setMethodArgs($params);
                $returnValue = $annotationDispatcher->dispatchAnnotationMethodCallBefore($methodName);

                if ($returnValue !== null) {
                    $returnEarly = true;
                }
                return $returnValue;
            };
        }
        return $beforeCall;
    }

    /**
     * @param \ReflectionMethod[] $methods
     * @param AbstractBean $bean
     * @return array
     */
    private function getAfterCallMethod($methods, AbstractBean $bean)
    {
        $afterCall = [];
        foreach ($methods as $method) {

            $methodName = $method->getName();

            $afterCall[$methodName] = function ($proxy, $instance, $method, $params, $returnValue, & $returnEarly) use ($methodName, $bean) {

                $annotationDispatcher = new AnnotationsDispatcher($bean, $bean->getClass(), $bean->getSphringEventDispatcher());
                $annotationDispatcher->setMethodArgs($params);
                if ($returnValue !== null) {
                    $returnEarly = true;
                }
                $toReturnAfter = $annotationDispatcher->dispatchAnnotationMethodCallAfter($methodName);
                if ($toReturnAfter !== null) {
                    $returnEarly = true;
                    $returnValue = $toReturnAfter;
                }
                return $returnValue;
            };
        }
        return $afterCall;
    }

    /**
     * @return AccessInterceptorValueHolderFactory
     */
    public function getProxyFactory()
    {
        return $this->proxyFactory;
    }

    /**
     * @param AccessInterceptorValueHolderFactory $proxyFactory
     */
    public function setProxyFactory(AccessInterceptorValueHolderFactory $proxyFactory)
    {
        $this->proxyFactory = $proxyFactory;
    }

}
