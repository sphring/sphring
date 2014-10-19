<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 19/10/2014
 */

namespace Arthurh\Sphring\Logger;


use Arthurh\Sphring\AbstractTestSphring;
use Monolog\Logger;

class LoggerTest extends AbstractTestSphring
{
    public function testAllLoginWithMonolog()
    {
        $logger = LoggerSphring::getInstance();
        $logger->alert("monolog test alert");
        $logger->critical("monolog test critical");
        $logger->debug("monolog test debug");
        $logger->emergency("monolog test emergency");
        $logger->error("monolog test error");
        $logger->info("monolog info test");
        $logger->notice("monolog notice test");
        $logger->warning("monolog warning test");
        $logger->log(Logger::INFO, "monolog second info");
    }

    public function testAllLoginNull()
    {
        $logger = LoggerSphring::getInstance();
        $monolog = $logger->getLogger();
        $logger->setLogger(null);
        $this->testAllLoginWithMonolog();
        $logger->setLogger($monolog);
    }

    public function testOption()
    {
        $logger = LoggerSphring::getInstance();
        $withClass = $logger->getWithClass();
        $withFile = $logger->getWithFile();
        $logger->setWithClass(true);
        $logger->setWithFile(true);
        $this->testAllLoginWithMonolog();
        $logger->setWithFile($withFile);
        $logger->setWithClass($withClass);
    }
}
 