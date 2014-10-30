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
use Arthurh\Sphring\Runner\SphringRunner;

/**
 * Class LoadContextAnnotation
 * @package Arthurh\Sphring\Model\Annotation
 */
class LoadContextAnnotation extends AbstractAnnotation
{

    /**
     * @return string
     */
    public static function getAnnotationName()
    {
        return "LoadContext";
    }

    /**
     * @throws \Arthurh\Sphring\Exception\SphringAnnotationException
     */
    public function run()
    {
        if (!$this->isInSphringRunner()) {
            throw new SphringAnnotationException("Error in bean '%s' in class annotation: Annotation '%s' required to be set on '%s' class.",
                $this->getBean()->getId(), get_class($this), SphringRunner::class);
        }
        $contextFile = $this->getData();
        $sphring = $this->getSphringEventDispatcher()->getSphring();
        if (!is_bool($contextFile)) {
            $sphring->setFilename($contextFile);
        }
    }
}
