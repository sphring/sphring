<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 14/10/2014
 */

namespace Arthurh\Sphring\Model\Bean;

use Arthurh\Sphring\Exception\BeanException;


/**
 * Class Bean
 * @package arthurh\sphring\model
 */
class Bean extends AbstractBean
{

    public function inject()
    {
        parent::inject();
        $this->startMethodInit();

    }

    private function startMethodInit()
    {
        if (empty($this->methodInit)) {
            return;
        }
        if (!is_string($this->methodInit)) {
            throw new BeanException($this, "Error when sphring start initialization method, method name is not a string.");
        }
        if (!method_exists($this->object, $this->methodInit)) {
            throw new BeanException($this, "Error when sphring start initialization method '%s' for object '%s'. Thie method doesn't exist.", $this->methodInit, $this->object);
        }
        $method = $this->methodInit;
        $this->object->$method();
    }

    public function getValidBeanFile()
    {
        return __DIR__ . '/../../Validation/Bean/bean.yml';
    }
}
