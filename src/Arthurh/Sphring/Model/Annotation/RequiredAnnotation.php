<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 15/10/2014
 */

namespace Arthurh\Sphring\Model\Annotation;

use Arthurh\Sphring\Exception\SphringAnnotationException;

/**
 * Class RequiredAnnotation
 * @package Arthurh\Sphring\Model\Annotation
 */
class RequiredAnnotation extends AbstractAnnotation
{

    /**
     * @return string
     */
    public static function getAnnotationName()
    {
        return "Required";
    }

    /**
     * @throws \Arthurh\Sphring\Exception\SphringAnnotationException
     */
    public function run()
    {
        if (!$this->isSetter()) {
            throw new SphringAnnotationException("Error for bean '%s', you can set required only on a setter.", $this->bean->getId());
        }
        $name = $this->reflector->getName();
        $fieldName = substr($name, 3, strlen($name) - 3);
        $requiredField = 'get' . $fieldName;
        $field = $this->getBean()->getObject()->$requiredField();
        if (empty($field)) {
            throw new SphringAnnotationException("Error for bean '%s' field '%s' is required.", $this->bean->getId(), lcfirst($fieldName));
        }
    }
}
