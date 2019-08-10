<?php
if(!isset($app)){
	$app = require_once __DIR__ . '/../bootstrap.php';
}
if($app->getEnvironment() === 'dev'){
	if(isset($_SERVER['HTTP_CLIENT_IP'])
		|| isset($_SERVER['HTTP_X_FORWARDED_FOR'])
		|| !(in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '10.1.1.1', '10.9.9.1', '192.168.43.165', 'fe80::1', 'fe80::5ab0:35ff:fe5c:f985')))
	){
		header('HTTP/1.0 403 Forbidden');
		exit('You are not allowed to access this file. Check ' . basename(__FILE__) . ' for more information.');
	}
}

$app->runWeb();
