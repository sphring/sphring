<?php
/**
 * Copyright (C) 2014 Orange
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 14/10/2014
 */


namespace Arthurh\Sphring\Exception;


/**
 * Class SphringException
 * @package arthurh\sphring\exception
 */
class SphringException extends \Exception
{


    /**
     * @param string $message
     */
    public function  __construct($message = "")
    {
        $previous = null;
        $args = func_get_args();
        $nbArgs = count($args);
        if ($args[$nbArgs - 1] instanceof \Exception) {
            $previous = $args[$nbArgs - 1];
            $args = array_splice($args, 0, $nbArgs - 1);
        }
        $message = call_user_func_array('sprintf', $args);
        parent::__construct($message, 0, $previous);
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

} 