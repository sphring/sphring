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

namespace Arthurh\Sphring\Exception;

use Arthurh\Sphring\Model\Bean\Bean;

/**
 * @see Arthurh\Sphring\Exception\SphringException
 * Class BeanException
 * @package arthurh\sphring\exception
 */
class BeanException extends SphringException
{
    /**
     * @param Bean $bean
     * @param string $message
     */
    public function  __construct(Bean $bean, $message = "")
    {
        $args = func_get_args();
        $args = array_splice($args, 1);
        $args[0] = sprintf("Error in bean '%s': ", $bean->getId()) . $args[0];
        call_user_func_array('parent::__construct', $args);
    }
}
