<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 13/03/2015
 */

namespace Arthurh\Sphring\Model\Annotation\AopAnnotation;


use Arthurh\Sphring\Exception\SphringAnnotationException;
use Arthurh\Sphring\Logger\LoggerSphring;

abstract class CallAnnotation extends AbstractAopAnnotation
{
    public function run()
    {
        $this->verify();
        $options = $this->getData();
        $beanId = $options->bean;
        $methodName = $options->method;
        $methodArgs = $this->getEvent()->getMethodArgs();
        try {
            $bean = $this->getSphringEventDispatcher()->getSphring()->getBean($beanId);
            if (!empty($options->condition) && !$this->evaluateExpressionBoolean($options->condition)
            ) {
                LoggerSphring::getInstance()->debug(
                    sprintf("Aop call '%s' on bean '%s' on method '%s' could not be triggered, condition '%s' was not satisfied.",
                        $this::getAnnotationName(), $this->getBean()->getId(), $methodName, $options->condition));
                return;
            }
            $args = array_merge([$bean], $methodArgs);

            $data = call_user_func_array(array($bean, $methodName), $args);
            if (!empty($options->return)) {
                $this->getEvent()->setData($data);
            }
            LoggerSphring::getInstance()->debug(sprintf("Aop call '%s' on bean '%s' on method '%s' triggered.",
                $this::getAnnotationName(), $this->getBean()->getId(), $methodName));
        } catch (\Exception $e) {
            throw new SphringAnnotationException("Annotation '%s' error: %s", $this::getAnnotationName(), $e->getMessage(), $e);
        }
    }

    public function verify()
    {
        if (!$this->isMethod()) {
            throw new SphringAnnotationException("Error for bean '%s', you can set %s only on a method.", $this->bean->getId(), $this::getAnnotationName());
        }
        $options = $this->getData();
        if (empty($options->bean)) {
            throw new SphringAnnotationException("Annotation '%s' require to set a bean.", $this::getAnnotationName());
        }
        if (empty($options->method)) {
            throw new SphringAnnotationException("Annotation '%s' require to set a method.", self::getAnnotationName());
        }
    }
}
