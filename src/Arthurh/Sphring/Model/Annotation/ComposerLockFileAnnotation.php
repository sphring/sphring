<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 22/03/2015
 */

namespace Arthurh\Sphring\Model\Annotation;


use Arthurh\Sphring\Exception\SphringAnnotationException;
use Arthurh\Sphring\Runner\SphringRunner;

class ComposerLockFileAnnotation extends AbstractAnnotation
{
    /**
     * @return string
     */
    public static function getAnnotationName()
    {
        return "ComposerLockFile";
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
        $composerLockFile = $this->getData();
        $sphring = $this->getSphringEventDispatcher()->getSphring();
        if (!is_string($composerLockFile)) {
            throw new SphringAnnotationException("Annotation '%s' require to set a filepath.", get_class($this));
        }
        $finaleComposerLockFile = $this->getComposerLockFile($composerLockFile);
        if (empty($finaleComposerLockFile)) {
            throw new SphringAnnotationException("Annotation '%s': '%s' is not a correct composer lock file.",
                get_class($this), $composerLockFile);
        }
        $sphring->setComposerLockFile($finaleComposerLockFile);
    }

    /**
     * @param string $composerLockFile
     *
     * @return string
     */
    private function getComposerLockFile($composerLockFile)
    {
        $filenameReflector = dirname($this->reflector->getFileName());
        if (is_file($filenameReflector . DIRECTORY_SEPARATOR . $composerLockFile)) {
            return $filenameReflector . DIRECTORY_SEPARATOR . $composerLockFile;
        }
        if (is_file($composerLockFile)) {
            return $composerLockFile;
        }
        $sphring = $this->getSphringEventDispatcher()->getSphring();
        if (is_file($sphring->getRootProject() . DIRECTORY_SEPARATOR . $composerLockFile)) {
            return $sphring->getRootProject() . DIRECTORY_SEPARATOR . $composerLockFile;
        }
        if (is_file($_SERVER['CONTEXT_DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $composerLockFile)) {
            return $_SERVER['CONTEXT_DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $composerLockFile;
        }
        if (is_file($_SERVER['SCRIPT_FILENAME'] . DIRECTORY_SEPARATOR . $composerLockFile)) {
            return $_SERVER['SCRIPT_FILENAME'] . DIRECTORY_SEPARATOR . $composerLockFile;
        }
        return null;
    }
}
