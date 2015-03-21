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

namespace Arthurh\Sphring\ComposerManager;

use Arhframe\Util\File;
use Arthurh\Sphring\Enum\SphringComposerEnum;
use Arthurh\Sphring\Extender\Extender;
use Arthurh\Sphring\Logger\LoggerSphring;

/**
 * This class will read your composer configuration to find any sphring plugin and extend your current sphring with theses sphring plugins
 * Class ComposerManager
 * @package Arthurh\Sphring\ComposerManager
 */
class ComposerManager
{
    /**
     * @var Extender
     */
    private $extender;
    /**
     * @var string
     */
    private $rootProject;

    /**
     * @var string
     */
    private $composerLockFile;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * Load information from composer.lock
     */
    public function loadComposer()
    {
        $composerLockFile = $this->getComposerLockFile();
        if ($composerLockFile === null) {
            return;
        }
        LoggerSphring::getInstance()->debug(sprintf("Loading composer lock file '%s'.", $composerLockFile));
        $composerLockFile = new File(realpath($composerLockFile));
        $decodedComposerLockFile = json_decode($composerLockFile->getContent(), true);
        $packages = $decodedComposerLockFile['packages'];
        foreach ($packages as $package) {
            $extras = $package[SphringComposerEnum::EXTRA_COMPOSER_KEY];
            if (empty($extras)) {
                continue;
            }
            $sphringExtra = $extras[SphringComposerEnum::EXTRA_SPHRING_COMPOSER_KEY];
            if (empty($sphringExtra)) {
                continue;
            }

            $this->loadSphringPlugin($package);

        }
    }

    /**
     * Return the composer.json by finding it in the project
     * @return null|string
     */
    public function getComposerLockFile()
    {
        if (!empty($this->composerLockFile)) {
            $composerFile = $this->composerLockFile;
        } else {
            $composerFile = SphringComposerEnum::COMPOSER_LOCK_FILE;
        }
        if (is_file($this->getRootProject() . DIRECTORY_SEPARATOR . $composerFile)) {
            return $this->getRootProject() . DIRECTORY_SEPARATOR . $composerFile;
        }
        if (is_file($composerFile)) {
            return $composerFile;
        }

        if (is_file($_SERVER['CONTEXT_DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $composerFile)) {
            return $_SERVER['CONTEXT_DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $composerFile;
        }
        return null;
    }

    /**
     * Return the root path project
     * @return string
     */
    public function getRootProject()
    {
        return $this->rootProject;
    }

    /**
     * Set the root path project
     * @param string $rootProject
     */
    public function setRootProject($rootProject)
    {
        $this->rootProject = $rootProject;
    }

    /**
     * Parse from composer.lock extra properties to extend the current sphring
     */
    private function loadSphringPlugin(array $packageSphring)
    {
        $extras = $packageSphring[SphringComposerEnum::EXTRA_COMPOSER_KEY];
        if (empty($extras)) {
            return;
        }
        $sphringExtra = $extras[SphringComposerEnum::EXTRA_SPHRING_COMPOSER_KEY];
        if (empty($sphringExtra)) {
            return;
        }
        if (empty($sphringExtra[SphringComposerEnum::EXTRA_SPHRING_EXTEND_COMPOSER_KEY])
            || empty($sphringExtra[SphringComposerEnum::EXTRA_SPHRING_ISPLUGIN_COMPOSER_KEY])
        ) {
            return;
        }
        LoggerSphring::getInstance()->info(sprintf("Extending from composer plugin '%s'.", $packageSphring['name']));
        $this->extendFromComposer($sphringExtra[SphringComposerEnum::EXTRA_SPHRING_EXTEND_COMPOSER_KEY]);

    }

    /**
     * Extend the current sphring
     * @param array $extendNodesArray
     */
    private function extendFromComposer(array $extendNodesArray)
    {
        foreach ($extendNodesArray as $extendNodeName => $extendNodeNameInfo) {
            $this->extender->addExtendFromArray($extendNodeName, $extendNodeNameInfo);
        }

    }

    /**
     * Get extender object
     * @return Extender
     */
    public function getExtender()
    {
        return $this->extender;
    }

    /**
     * Set extender object
     * @param Extender $extender
     */
    public function setExtender(Extender $extender)
    {
        $this->extender = $extender;
    }

    /**
     * @param string $composerLockFile
     */
    public function setComposerLockFile($composerLockFile)
    {
        $this->composerLockFile = $composerLockFile;
    }


}
