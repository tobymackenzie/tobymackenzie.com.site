<?php
namespace TJM\SyWeb;

//--configure paths
const APP_DIR = __DIR__ . '/..';
require(APP_DIR . '/vendor/autoload.php');
//--build app
$app = new App([
	'bundles'=> [
		'Symfony\Bundle\FrameworkBundle\FrameworkBundle',
		'Symfony\Bundle\TwigBundle\TwigBundle',
		'Symfony\Bundle\MonologBundle\MonologBundle',
		'TJM\SySite\TJMSySiteBundle',
		'TJM\WikiBlog\TJMWikiBlogBundle',
		'TJM\WikiSiteBundle\TJMWikiSiteBundle',
		'Symfony\Bundle\DebugBundle\DebugBundle'=> ['dev', 'test'],
		'Symfony\Bundle\WebProfilerBundle\WebProfilerBundle'=> ['dev', 'test'],
	],
	'paths'=> [
		'app'=> APP_DIR,
		'project'=> APP_DIR,
	],
]);
if(getenv('TMWEB_DEV')){
	$app->setEnvironment('dev');
}
return $app;
