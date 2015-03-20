<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 20/03/2015
 */

namespace Arthurh\Sphring\SphringBoot;


use Arthurh\Sphring\Enum\SphringYamlarhConstantEnum;
use Arthurh\Sphring\Sphring;
use Arthurh\Sphring\YamlarhNode\PopertyFileYamlarhNode;

class SphringBootYamlarhEnv
{

    /**
     * @var Sphring
     */
    private $sphring;

    function __construct(Sphring $sphring)
    {
        $this->sphring = $sphring;
    }

    public function boot()
    {
        $this->injectConstantInYamlarh();
        $this->injectPropertiesEnv();
        $this->bootYamlarhNode();
    }

    private function bootYamlarhNode()
    {
        $this->sphring->getYamlarh()->addNode(SphringYamlarhConstantEnum::PROPERTY_FILE_NODE, new PopertyFileYamlarhNode());
    }

    private function injectConstantInYamlarh()
    {
        if ($this->sphring->getYamlarh() === null) {
            return;
        }
        $this->sphring->getYamlarh()->addAccessibleVariable(SphringYamlarhConstantEnum::ROOTPROJECT, $this->sphring->getRootProject());
        $this->sphring->getYamlarh()->addAccessibleVariable(SphringYamlarhConstantEnum::SERVER, $_SERVER);
        $this->sphring->getYamlarh()->addAccessibleVariable(SphringYamlarhConstantEnum::POST, $_POST);
        $this->sphring->getYamlarh()->addAccessibleVariable(SphringYamlarhConstantEnum::GET, $_GET);
    }

    private function injectPropertiesEnv()
    {
        if (isset($_ENV) && !empty($_ENV)) {
            $this->injectPropertiesEnvByVar($_ENV);
        }

    }

    private function injectPropertiesEnvByVar(array $propertiesEnv)
    {
        if ($this->sphring->getYamlarh() === null) {
            return;
        }
        $yamlarh = $this->sphring->getYamlarh();
        foreach ($propertiesEnv as $propertiesEnvKey => $propertiesEnvValue) {

            $yamlarh->addAccessibleVariable(strtolower($propertiesEnvKey), $propertiesEnvValue);
        }

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
    public function setSphring(Sphring $sphring)
    {
        $this->sphring = $sphring;
    }


}
