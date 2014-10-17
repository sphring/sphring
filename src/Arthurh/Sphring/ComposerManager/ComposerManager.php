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


use Arthurh\Sphring\Enum\SphringComposerEnum;
use Arthurh\Sphring\Extender\Extender;
use Arthurh\Sphring\Logger\LoggerSphring;
use Composer\Composer;
use Composer\Factory;
use Composer\IO\NullIO;

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
     * @var Composer
     */
    private $composer;

    public function __construct()
    {
    }

    public function loadComposer()
    {
        $composerFile = $this->getComposerFile();
        if ($composerFile === null) {
            return;
        }
        LoggerSphring::getInstance()->debug(sprintf("Loading composer file '%s'.", $composerFile));
        $this->composer = Factory::create(new NullIO(), $composerFile);
        $packages = $this->composer->getLocker()->getLockData()['packages'];
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

    public function getComposerFile()
    {
        $composerFile = basename(Factory::getComposerFile());
        if (is_file($this->getRootProject() . DIRECTORY_SEPARATOR . $composerFile)) {
            return $this->getRootProject() . DIRECTORY_SEPARATOR . $composerFile;
        }
        if (is_file(Factory::getComposerFile())) {
            return Factory::getComposerFile();
        }

        if (is_file($_SERVER['CONTEXT_DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $composerFile)) {
            return $_SERVER['CONTEXT_DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $composerFile;
        }
        return null;
    }

    /**
     * @return string
     */
    public function getRootProject()
    {
        return $this->rootProject;
    }

    /**
     * @param string $rootProject
     */
    public function setRootProject($rootProject)
    {
        $this->rootProject = $rootProject;
    }

    /**
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

    private function extendFromComposer(array $extendNodesArray)
    {
        foreach ($extendNodesArray as $extendNodeName => $extendNodeNameInfo) {
            $this->extender->addExtendFromArray($extendNodeName, $extendNodeNameInfo);
        }

    }

    /**
     * @return Extender
     */
    public function getExtender()
    {
        return $this->extender;
    }

    /**
     * @param Extender $extender
     */
    public function setExtender(Extender $extender)
    {
        $this->extender = $extender;
    }

    /**
     * @return Composer
     */
    public function getComposer()
    {
        return $this->composer;
    }


}
