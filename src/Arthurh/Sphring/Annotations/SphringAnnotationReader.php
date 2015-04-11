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


use Arthurh\Sphring\Enum\SphringComposerEnum;
use Arthurh\Sphring\Exception\SphringException;
use Arthurh\Sphring\Sphring;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;

class SphringAnnotationReader implements Reader
{
    /**
     * @var Reader
     */
    private $reader;
    /**
     * @var Sphring
     */
    private $sphring;

    public function __construct(Sphring $sphring)
    {
        $this->reader = new AnnotationReader();
        $this->sphring = $sphring;
        $this->initReader();
    }

    public function initReader()
    {
        $file = $this->sphring->getRootProject() . DIRECTORY_SEPARATOR . SphringComposerEnum::AUTLOADER_FILE;
        if (!is_file($file)) {
            $file = $this->sphring->getContextRoot() . DIRECTORY_SEPARATOR . SphringComposerEnum::AUTLOADER_FILE;
        }
        if (!is_file($file)) {
            $file = SphringComposerEnum::AUTLOADER_FILE;
        }
        if (!is_file($file)) {
            throw new SphringException("Can't found autoloader for annotation");
        }
        $loader = require $file;
        AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
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

}
