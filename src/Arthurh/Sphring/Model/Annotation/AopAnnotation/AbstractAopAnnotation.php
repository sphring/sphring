<?php
/**
 * Copyright (C) 2015 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 24/03/2015
 */


namespace Arthurh\Sphring\Model\Annotation\AopAnnotation;


use Arthurh\Sphring\Exception\SphringAnnotationException;
use Arthurh\Sphring\Model\Annotation\AbstractAnnotation;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

abstract class AbstractAopAnnotation extends AbstractAnnotation
{
    protected function evaluateExpressionBoolean($condition)
    {
        $evaluate = $this->evaluateExpression($condition);
        if (!is_bool($evaluate)) {
            throw new SphringAnnotationException("Error for bean '%s', in annotation '%s' condition must be a boolean expression result.", $this->bean->getId(), $this::getAnnotationName());
        }
        return $evaluate;
    }

    protected function evaluateExpression($condition)
    {
        $rm = $this->getEvent()->getReflector();
        $args = $this->getEvent()->getMethodArgs();
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
