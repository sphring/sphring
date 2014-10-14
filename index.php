<?php
require_once __DIR__ . '/vendor/autoload.php';
ini_set("display_errors", "On");
ini_set("error_reporting", E_ALL & ~E_NOTICE);
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$bean = new \Arthurh\Sphring\Model\Bean("jojo");
$bean->setJuju(array('value' => array('koi' => 'quoi')));
echo $bean->getJuju();
//$bean->jojo();
