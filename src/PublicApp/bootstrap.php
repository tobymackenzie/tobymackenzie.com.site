<?php
namespace TJM\SyWeb;

//--Define configuration used by App singleton, such as paths.
define(__NAMESPACE__ . '\APP_DIR', realpath(__DIR__));
define(__NAMESPACE__ . '\PROJECT_DIR', realpath(APP_DIR . '/../..'));
define(__NAMESPACE__ . '\VENDOR_DIR', PROJECT_DIR . '/vendor');
$loader = require(VENDOR_DIR . '/autoload.php');
$app = new App([
	'allowedDevIPs'=> ['127.0.0.1', '::1', '192.168.56.1'],
	'bundles'=> [
		'Symfony\Bundle\FrameworkBundle\FrameworkBundle',
		'Symfony\Bundle\TwigBundle\TwigBundle',
		'Symfony\Bundle\MonologBundle\MonologBundle',
		'TJM\SySite\TJMSySiteBundle',
		'Symfony\Bundle\DebugBundle\DebugBundle'=> ['dev', 'test'],
		'Symfony\Bundle\WebProfilerBundle\WebProfilerBundle'=> ['dev', 'test'],
	],
	//-# uncomment to enable Symfony's HTTPCache in 'prod' environment
	// 'cache'=> 'prod',
	'loader'=> $loader,
	'paths'=> [
		//--symfony paths
		'app'=> __DIR__,
		'project'=> PROJECT_DIR,
		'src'=> PROJECT_DIR . '/src',
		'var'=> PROJECT_DIR . '/var',
		'vendor'=> VENDOR_DIR,
	],
]);
return $app;
