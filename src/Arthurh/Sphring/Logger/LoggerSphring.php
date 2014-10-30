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

namespace Arthurh\Sphring\Logger;

use Psr\Log\LoggerInterface;

/**
 * Singleton class use to log Sphring, you must pass a LoggerInterface implementation to this class
 * Class LoggerSphring
 * @package Arthurh\Sphring\Logger
 */
class LoggerSphring implements LoggerInterface
{
    /**
     *
     */
    const DEFAULT_MESSAGE = "Sphring: '%s' : ";
    /**
     * @var LoggerSphring
     */
    private static $_instance = null;
    /**
     * @var LoggerInterface
     */
    private $logger = null;

    /**
     * @var bool
     */
    private $withFile = false;
    /**
     * @var bool
     */
    private $withClass = true;

    /**
     *
     */
    private function __construct()
    {

    }

    /**
     * Return the LoggerSphring
     * @return LoggerSphring
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new LoggerSphring();
        }

        return self::$_instance;
    }

    /**
     * Get the LoggerInterface
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Set the LoggerInterface
     * @param LoggerInterface $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function emergency($message, array $context = array())
    {
        if (empty($this->logger)) {
            return;
        }
        $this->logger->emergency($this->getDefaultMessage() . $message, $context);
    }

    /**
     * @return string
     */
    private function getDefaultMessage()
    {
        return sprintf(self::DEFAULT_MESSAGE, $this->getCallerInfo());
    }

    /**
     * @return string
     */
    private function getCallerInfo()
    {

        $info = "";
        $trace = debug_backtrace();

        if (!isset($trace[3]) || !isset($trace[3]['class'])) {
            return "";
        }
        $func = $trace[3]['function'];
        if ((substr($func, 0, 7) == 'include') || (substr($func, 0, 7) == 'require')) {
            $func = '';
        }
        $line = $trace[2]['line'];
        if ($this->withFile) {
            $info .= $trace[2]['file'] . "(" . $line . "): ";
        }
        if ($this->withClass) {
            $func = $trace[3]['function'];
            $info .= $trace[3]['class'] . "->";
        }
        $info .= $func . "(" . $line . ")";
        return $info;
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function alert($message, array $context = array())
    {
        if (empty($this->logger)) {
            return;
        }
        $this->logger->alert($this->getDefaultMessage() . $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function critical($message, array $context = array())
    {
        if (empty($this->logger)) {
            return;
        }
        $this->logger->critical($this->getDefaultMessage() . $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function error($message, array $context = array())
    {
        if (empty($this->logger)) {
            return;
        }
        $this->logger->error($this->getDefaultMessage() . $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function warning($message, array $context = array())
    {
        if (empty($this->logger)) {
            return;
        }
        $this->logger->warning($this->getDefaultMessage() . $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function notice($message, array $context = array())
    {
        if (empty($this->logger)) {
            return;
        }
        $this->logger->notice($this->getDefaultMessage() . $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function info($message, array $context = array())
    {
        if (empty($this->logger)) {
            return;
        }
        $this->logger->info($this->getDefaultMessage() . $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public function debug($message, array $context = array())
    {
        if (empty($this->logger)) {
            return;
        }
        $this->logger->debug($this->getDefaultMessage() . $message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        if (empty($this->logger)) {
            return;
        }
        $this->logger->log($level, $this->getDefaultMessage() . $message, $context);
    }

    /**
     * Return true if logger will give the file which logging
     * @return boolean
     */
    public function getWithFile()
    {
        return $this->withFile;
    }

    /**
     * Set to true if you want logger gave the file which logging
     * @param boolean $withFile
     */
    public function setWithFile($withFile)
    {
        $this->withFile = $withFile;
    }

    /**
     * Return true if logger will give the class which logging
     * @return boolean
     */
    public function getWithClass()
    {
        return $this->withClass;
    }

    /**
     * Set to true if you want logger gave the class which logging
     * @param boolean $withClass
     */
    public function setWithClass($withClass)
    {
        $this->withClass = $withClass;
    }

}
