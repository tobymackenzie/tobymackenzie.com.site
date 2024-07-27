<?php
namespace TJM\SyWeb;

//--configure paths
const APP_DIR = __DIR__;
const PROJECT_DIR = APP_DIR . '/../..';
require(PROJECT_DIR . '/vendor/autoload.php');
//--build app
$app = new App([
	'allowedDevIPs'=> ['127.0.0.1', '::1', '192.168.56.1'],
	'bundles'=> [
		'Symfony\Bundle\FrameworkBundle\FrameworkBundle',
		'Symfony\Bundle\TwigBundle\TwigBundle',
		'Symfony\Bundle\MonologBundle\MonologBundle',
		'TJM\SySite\TJMSySiteBundle',
		'TJM\WikiSiteBundle\TJMWikiSiteBundle',
		'Symfony\Bundle\DebugBundle\DebugBundle'=> ['dev', 'test'],
		'Symfony\Bundle\WebProfilerBundle\WebProfilerBundle'=> ['dev', 'test'],
	],
	'paths'=> [
		'app'=> APP_DIR,
		'project'=> PROJECT_DIR,
	],
]);
return $app;
