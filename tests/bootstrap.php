<?php
$loader = require_once __DIR__ . '/../vendor/autoload.php';
$loader->add('Arthurh\\Sphring\\', __DIR__);
$logger = new \Monolog\Logger("SphringTest");
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::DEBUG));
\Arthurh\Sphring\Logger\LoggerSphring::getInstance()->setLogger($logger);
ini_set("variables_order", "EGPCS");
return $loader;