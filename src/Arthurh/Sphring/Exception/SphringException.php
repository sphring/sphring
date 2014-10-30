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

/**
 * The SphringException, it has a different way to leverage exception.
 * You can pass a message in sprintf style and pass your arguments for sprintf on it.
 * If you pass an Exception object at the end it will passed to Exception constructor
 * Class SphringException
 * @package arthurh\sphring\exception
 */
class SphringException extends \Exception
{

    /**
     * Constructor
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
     * Set the error code
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

}
