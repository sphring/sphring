<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 14/03/2015
 */

namespace Arthurh\Sphring\Validation;


use Arhframe\Yamlarh\Yamlarh;
use Arthurh\Sphring\EventDispatcher\Listener\BeanPropertyListener;
use Arthurh\Sphring\Exception\SphringException;
use RomaricDrigon\MetaYaml\MetaYaml;

class Validator
{
    const YAMLARHBEANPROPERTY = 'beanproperties';
    /**
     * @var Yamlarh
     */
    private $yamlarh;

    /**
     * @var Validator
     */
    private static $_instance = null;
    /**
     * @var BeanPropertyListener
     */
    private $beanPropertyListener;

    private function __construct()
    {
        $this->yamlarh = new Yamlarh(null);
    }

    /**
     * @return Validator
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Validator();
        }
        return self::$_instance;
    }


    public function validate($yamlFile, $dataToValidate, $validateSchema = false)
    {
        if (empty($yamlFile)) {
            return true;
        }
        if (!is_file($yamlFile)) {
            throw new SphringException("File '%s' cannot be found.", $yamlFile);
        }
        $this->yamlarh->setFileName($yamlFile);
        $schema = $this->yamlarh->parse();
        $schema = new MetaYaml($schema, $validateSchema);
        return $schema->validate($dataToValidate);
    }

    /**
     * @return BeanPropertyListener
     */
    public function getBeanPropertyListener()
    {
        return $this->beanPropertyListener;
    }

    /**
     * @param BeanPropertyListener $beanPropertyListener
     */
    public function setBeanPropertyListener(BeanPropertyListener $beanPropertyListener)
    {
        if ($beanPropertyListener === $this->beanPropertyListener) {
            return;
        }
        $this->beanPropertyListener = $beanPropertyListener;
        $beanPropertiesToInject = [];
        $registers = $this->beanPropertyListener->getRegisters();
        foreach ($registers as $eventName => $className) {
            $propertyName = explode('.', $eventName);
            $propertyName = $propertyName[count($propertyName) - 1];
            $validation = $className::getValidation();
            if ($validation === null) {
                continue;
            }
            $beanPropertiesToInject[$propertyName] = $validation;
        }
        $this->yamlarh->addAccessibleVariable(self::YAMLARHBEANPROPERTY, $beanPropertiesToInject);
    }

}
