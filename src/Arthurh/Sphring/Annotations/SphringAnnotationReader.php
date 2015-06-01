<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 11/04/2015
 */

namespace Arthurh\Sphring\Annotations;


use Arthurh\Sphring\ComposerManager\ComposerManager;
use Arthurh\Sphring\Enum\SphringComposerEnum;
use Arthurh\Sphring\Logger\LoggerSphring;
use Arthurh\Sphring\Sphring;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use Arthurh\Sphring\Exception\SphringAnnotationException;

class SphringAnnotationReader implements Reader
{
    /**
     * @var ComposerManager
     */
    private $composerManager;
    /**
     * @var Reader
     */
    private $reader;
    /**
     * @var Sphring
     */
    private $sphring;

    public function __construct(Sphring $sphring, ComposerManager $composerManager)
    {
        $this->reader = new AnnotationReader();
        $this->sphring = $sphring;
        $this->composerManager = $composerManager;
    }

    public function initReader()
    {
        LoggerSphring::getInstance()->info("Initiating registering annotation");
        $file = $this->sphring->getRootProject() . DIRECTORY_SEPARATOR . SphringComposerEnum::AUTLOADER_FILE;
        if (!is_file($file)) {
            $file = $this->sphring->getContextRoot() . DIRECTORY_SEPARATOR . SphringComposerEnum::AUTLOADER_FILE;
        }
        if (!is_file($file)) {
            $file = $this->getAutoloaderFromLibrary();
        }
        if (!is_file($file)) {
            $file = $_SERVER['CONTEXT_DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . SphringComposerEnum::AUTLOADER_FILE;
        }
        if (!is_file($file)) {
            $file = dirname($this->composerManager->getComposerLockFile()) . DIRECTORY_SEPARATOR . SphringComposerEnum::AUTLOADER_FILE;
        }
        if (!is_file($file)) {
            throw new SphringAnnotationException("Can't found autoloader for annotation reading.");
        }
        $loader = require $file;
        AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
    }
    /**
     * @return string
     */
    private function getAutoloaderFromLibrary()
    {
        $file = SphringComposerEnum::AUTLOADER_FILE;
        if (empty(ini_get('include_path'))) {
            return $file;
        }
        if (DIRECTORY_SEPARATOR == '/') {
            $ps = explode(":", ini_get('include_path'));
        } else {
            $ps = explode(";", ini_get('include_path'));
        }
        foreach ($ps as $path) {
            if (file_exists($path . DIRECTORY_SEPARATOR . $file)) {
                return $path . DIRECTORY_SEPARATOR . $file;
            }
        }
        return $file;
    }
    /**
     * Gets the annotations applied to a class.
     *
     * @param \ReflectionClass $class The ReflectionClass of the class from which
     *                                the class annotations should be read.
     *
     * @return array An array of Annotations.
     */
    function getClassAnnotations(\ReflectionClass $class)
    {
        return $this->reader->getClassAnnotations($class);
    }

    /**
     * Gets a class annotation.
     *
     * @param \ReflectionClass $class The ReflectionClass of the class from which
     *                                         the class annotations should be read.
     * @param string $annotationName The name of the annotation.
     *
     * @return object|null The Annotation or NULL, if the requested annotation does not exist.
     */
    function getClassAnnotation(\ReflectionClass $class, $annotationName)
    {
        return $this->reader->getClassAnnotation($class, $annotationName);
    }

    /**
     * Gets the annotations applied to a method.
     *
     * @param \ReflectionMethod $method The ReflectionMethod of the method from which
     *                                  the annotations should be read.
     *
     * @return array An array of Annotations.
     */
    function getMethodAnnotations(\ReflectionMethod $method)
    {
        return $this->reader->getMethodAnnotations($method);
    }

    /**
     * Gets a method annotation.
     *
     * @param \ReflectionMethod $method The ReflectionMethod to read the annotations from.
     * @param string $annotationName The name of the annotation.
     *
     * @return object|null The Annotation or NULL, if the requested annotation does not exist.
     */
    function getMethodAnnotation(\ReflectionMethod $method, $annotationName)
    {
        return $this->reader->getMethodAnnotation($method, $annotationName);
    }

    /**
     * Gets the annotations applied to a property.
     *
     * @param \ReflectionProperty $property The ReflectionProperty of the property
     *                                      from which the annotations should be read.
     *
     * @return array An array of Annotations.
     */
    function getPropertyAnnotations(\ReflectionProperty $property)
    {
        return $this->reader->getPropertyAnnotations($property);
    }

    /**
     * Gets a property annotation.
     *
     * @param \ReflectionProperty $property The ReflectionProperty to read the annotations from.
     * @param string $annotationName The name of the annotation.
     *
     * @return object|null The Annotation or NULL, if the requested annotation does not exist.
     */
    function getPropertyAnnotation(\ReflectionProperty $property, $annotationName)
    {
        return $this->reader->getPropertyAnnotation($property, $annotationName);
    }

    /**
     * @return Reader
     */
    public function getReader()
    {
        return $this->reader;
    }

    /**
     * @param Reader $reader
     */
    public function setReader($reader)
    {
        $this->reader = $reader;
    }

    /**
     * @return Sphring
     */
    public function getSphring()
    {
        return $this->sphring;
    }

    /**
     * @param Sphring $sphring
     */
    public function setSphring($sphring)
    {
        $this->sphring = $sphring;
    }

    /**
     * @return ComposerManager
     */
    public function getComposerManager()
    {
        return $this->composerManager;
    }

    /**
     * @param ComposerManager $composerManager
     */
    public function setComposerManager($composerManager)
    {
        $this->composerManager = $composerManager;
    }

}
