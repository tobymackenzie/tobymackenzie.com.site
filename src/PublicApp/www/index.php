<?php
if(!isset($app)){
	$app = require_once __DIR__ . '/../bootstrap.php';
}
if(getenv('TMWEB_DEV')){
	$app->setEnvironment('dev');
}
$app();
