<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 17/10/2014
 */

namespace Arthurh\Sphring\Model\Annotation;

use Arthurh\Sphring\Exception\SphringAnnotationException;
use Arthurh\Sphring\Runner\SphringRunner;

/**
 * Class RootProjectAnnotation
 * @package Arthurh\Sphring\Model\Annotation
 */
class RootProjectAnnotation extends AbstractAnnotation
{
    /**
     * @return string
     */
    public static function getAnnotationName()
    {
        return "RootProject";
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
        $rootProject = $this->getData();
        $sphring = $this->getSphringEventDispatcher()->getSphring();
        if (!is_string($rootProject)) {
            throw new SphringAnnotationException("Annotation '%s' require to set a filepath.", get_class($this));
        }
        $finalRootProject = $this->getRootProject($rootProject);
        if (empty($finalRootProject)) {
            throw new SphringAnnotationException("Annotation '%s': '%s' is not a correct folder.",
                get_class($this), $rootProject);
        }
        $sphring->setRootProject($finalRootProject);
    }

    /**
     * @param string $rootProject
     *
     * @return string
     */
    private function getRootProject($rootProject)
    {
        $dirnameReflector = dirname($this->reflector->getFileName());
        if (is_dir($dirnameReflector . DIRECTORY_SEPARATOR . $rootProject)) {
            return $dirnameReflector . DIRECTORY_SEPARATOR . $rootProject;
        }
        if (is_dir($rootProject)) {
            return $rootProject;
        }
        if (is_dir($_SERVER['CONTEXT_DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $rootProject)) {
            return $_SERVER['CONTEXT_DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $rootProject;
        }
        if (is_dir($_SERVER['SCRIPT_FILENAME'] . DIRECTORY_SEPARATOR . $rootProject)) {
            return $_SERVER['SCRIPT_FILENAME'] . DIRECTORY_SEPARATOR . $rootProject;
        }
        return null;
    }
}
