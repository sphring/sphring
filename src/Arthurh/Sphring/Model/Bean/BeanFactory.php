<?php
/**
 * Copyright (C) 2015 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 21/05/2015
 */


namespace Arthurh\Sphring\Model\Bean;


use Arthurh\Sphring\Exception\BeanException;

class BeanFactory extends AbstractBean
{
    protected $beanId;
    protected $beanMethod;

    public function inject()
    {
        try {
            $propertyClass = $this->getPropertyFromEvent('ref', $this->beanId);
            $objectFactory = $propertyClass->inject();
        } catch (\Exception $e) {
            throw new BeanException($this, $e->getMessage());
        }
        if (!method_exists($objectFactory, $this->beanMethod)) {
            throw new BeanException($this, "method '%s' doesn't exist for bean '%s'", $this->beanMethod, $this->beanId);
        }
        $this->checkBeanFactory($objectFactory);
        $this->object = call_user_func_array([$objectFactory, $this->beanMethod], $this->constructor);
    }

    protected function checkBeanFactory($objectFactory)
    {
        $reflectionFactory = new \ReflectionClass(get_class($objectFactory));
        $reflectionMethod = $reflectionFactory->getMethod($this->beanMethod);
        if (!$reflectionMethod->isPublic()) {
            throw new BeanException($this, "method '%s' from bean '%s' is not a public method", $this->beanMethod, $this->beanId);
        }
        $this->checkParamsPassed($reflectionMethod);
    }

    protected function checkParamsPassed(\ReflectionMethod $reflectionMethod)
    {

        $reflectionParams = $reflectionMethod->getParameters();
        $requiredArgs = $reflectionParams;
        $inError = false;
        foreach ($reflectionParams as $i => $reflectionParam) {
            if (!$reflectionParam->isOptional()) {
                $requiredArgs = $reflectionParam->getName();
                $inError = true;
            }
            if (isset($this->constructor[$i])) {
                $inError = false;
                continue;
            }

        }
        if ($inError) {
            throw new BeanException($this, "method '%s' from bean '%s' require arguments '%'", $this->beanMethod, $this->beanId, implode(', ', $requiredArgs));
        }
    }

    public function getValidBeanFile()
    {
        return __DIR__ . '/../../Validation/Bean/beanFactory.yml';
    }

    /**
     * @return mixed
     */
    public function getBeanId()
    {
        return $this->beanId;
    }

    /**
     * @param mixed $beanId
     */
    public function setBeanId($beanId)
    {
        $this->beanId = $beanId;
    }

    /**
     * @return mixed
     */
    public function getBeanMethod()
    {
        return $this->beanMethod;
    }

    /**
     * @param mixed $beanMethod
     */
    public function setBeanMethod($beanMethod)
    {
        $this->beanMethod = $beanMethod;
    }

}
