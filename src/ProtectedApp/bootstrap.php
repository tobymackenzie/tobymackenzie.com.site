<?php
namespace TJM\SyWeb;

//--Define configuration used by App singleton, such as paths.
define(__NAMESPACE__ . '\PROJECT_DIR', realpath(__DIR__ . '/../..'));
define(__NAMESPACE__ . '\VENDOR_DIR', PROJECT_DIR . '/vendor');
$loader = require(VENDOR_DIR . '/autoload.php');
$app = new App([
	'allowedDevIPs'=> ['127.0.0.1', '::1', '10.9.9.1'],
	'bundles'=> [
		'Symfony\Bundle\FrameworkBundle\FrameworkBundle'
		,'Symfony\Bundle\SecurityBundle\SecurityBundle'
		,'Symfony\Bundle\TwigBundle\TwigBundle'
		,'Symfony\Bundle\MonologBundle\MonologBundle'
		,'TJM\Bundle\BaseBundle\TJMBaseBundle'
		,'Symfony\Bundle\DebugBundle\DebugBundle'=> ['dev', 'test']
		,'Symfony\Bundle\WebProfilerBundle\WebProfilerBundle'=> ['dev', 'test']
	]
	,'loader'=> $loader
	,'name'=> 'protected'
	,'paths'=> [
		//--symfony paths
		'app'=> __DIR__
		,'project'=> PROJECT_DIR
		,'src'=> PROJECT_DIR . '/src'
		,'var'=> PROJECT_DIR . '/var'
		,'vendor'=> VENDOR_DIR

	]
]);

return $app;
