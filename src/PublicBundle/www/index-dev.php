<?php
use TJM\Bundle\StandardEditionBundle\Component\App\App;

$app = require_once __DIR__ . '/../bootstrap.php';
$app->setEnvironment('dev');
require __DIR__ . '/index.php';
