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

namespace Arthurh\Sphring\Model\Annotation;


use Arthurh\Sphring\Exception\SphringAnnotationException;
use Arthurh\Sphring\Model\Bean\ProxyBean;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

abstract class CallAnnotation extends AbstractAnnotation
{
    public function run()
    {
        if (!$this->isMethod()) {
            throw new SphringAnnotationException("Error for bean '%s', you can set %s only on a method.", $this->bean->getId(), self::getAnnotationName());
        }
        $options = $this->getData();
        if (empty($options['bean'])) {
            throw new SphringAnnotationException("Annotation '%s' require to set a bean.", self::getAnnotationName());
        }
        if (empty($options['method'])) {
            throw new SphringAnnotationException("Annotation '%s' require to set a method.", self::getAnnotationName());
        }
        $beanId = $options['bean'];
        $methodName = $options['method'];
        $methodArgs = $this->getEvent()->getMethodArgs();
        try {
            $bean = $this->getSphringEventDispatcher()->getSphring()->getBean($beanId);
            if (!empty($options['condition']) && !$this->evaluateExpression($this->getEvent()->getReflector(), $methodArgs, $options['condition'])) {
                return;
            }
            $args = array_merge([$bean], $methodArgs);
            $data = call_user_func_array(array($bean, $methodName), $args);
            if (!empty($options['return'])) {
                $this->getEvent()->setData($data);
            }
        } catch (\Exception $e) {
            throw new SphringAnnotationException("Annotation '%s' error: %s", $this::getAnnotationName(), $e->getMessage());
        }
    }

    private function evaluateExpression(\ReflectionMethod $rm, $args, $condition)
    {
        $params = $rm->getParameters();
        $nbArgs = count($args);
        $sfLanguage = new ExpressionLanguage();
        $valuesExpr = [];
        for ($i = 0; $i < $nbArgs; $i++) {
            echo $params[$i]->getName() . "\n";
            $valuesExpr[$params[$i]->getName()] = $args[0];
        }
        return $sfLanguage->evaluate($condition, $valuesExpr);
    }
}
