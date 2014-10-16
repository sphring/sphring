<?php
ini_set("variables_order", "EGPCS");
error_reporting(E_ALL & ~E_NOTICE);
$loader = require_once __DIR__ . '/../vendor/autoload.php';
$loader->add('Arthurh\\Sphring\\', __DIR__);
$logger = new \Monolog\Logger("SphringTest");
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::INFO));
$loggerSphring = \Arthurh\Sphring\Logger\LoggerSphring::getInstance();
$loggerSphring->setLogger($logger);
$loggerSphring->setWithFile(false);
$loggerSphring->setWithClass(true);

return $loader;