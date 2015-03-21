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

namespace Arthurh\Sphring\YamlarhNode;


use Arhframe\Util\File;
use Arhframe\Yamlarh\FileLoader;
use Arhframe\Yamlarh\YamlarhNode\AbstractYamlarhNode;
use Arthurh\Sphring\Exception\SphringException;

class PopertyFileYamlarhNode extends AbstractYamlarhNode
{
    private static $extIni = ["properties", "ini"];

    public function run()
    {
        $arrayYaml = $this->yamlarh->getArrayToReturn();
        $keyToFind = $this->yamlarh->getParamaterKey() . $this->nodeName;

        if (empty($arrayYaml[$keyToFind])) {
            return $arrayYaml;
        }
        foreach ($arrayYaml[$keyToFind] as $filename) {
            $this->loadFromFile($filename);
        }
        unset($arrayYaml[$keyToFind]);
        return $arrayYaml;
    }

    private function loadFromFile($filename)
    {
        $currentFile = new File($this->yamlarh->getFilename());
        $file = new File($filename);
        if (!$file->isFile()) {
            $file->setFolder($currentFile->getFolder() . $file->getFolder());
        }
        if (!$file->isFile()) {
            throw new SphringException("Property file '%s' cannot be found.", $file->getName());
        }
        $properties = FileLoader::loadFile($file);

        if ($properties === null && in_array($file->getExtension(), self::$extIni)) {
            $ini = new \Zend_Config_Ini($file->absolute());
            $properties = $ini->toArray();

        } elseif ($properties === null) {
            return;
        }
        $this->injectInYamlarh($properties);
    }

    private function injectInYamlarh(array $properties)
    {
        foreach ($properties as $key => $value) {
            $this->yamlarh->addAccessibleVariable($key, $value);
        }
    }
}
