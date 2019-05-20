<?php
// use Symfony\Component\ClassLoader\ApcClassLoader;
use TJM\Bundle\StandardEditionBundle\Component\App\App;

$loader = require_once __DIR__ . '/../bootstrap.php';

if(App::getEnvironment() === 'dev'){
	if(isset($_SERVER['HTTP_CLIENT_IP'])
		|| isset($_SERVER['HTTP_X_FORWARDED_FOR'])
		|| !(in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '10.9.9.1')))
	){
		header('HTTP/1.0 403 Forbidden');
		exit('You are not allowed to access this file. Check ' . basename(__FILE__) . ' for more information.');
	}
}elseif(App::getEnvironment() === 'prod' && PHP_VERSION_ID < 70000){
	include_once __DIR__ . '/../../var/bootstrap.php.cache';
	/*
	$loader = new ApcClassLoader('sf2', $loader);
	$loader->register(true);
	*/
}

App::runWeb();
