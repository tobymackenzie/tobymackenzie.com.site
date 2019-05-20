<?php
namespace TJM\Bundle\StandardEditionBundle\Component\App;
use Composer\Autoload\ClassLoader;

error_reporting(error_reporting() & ~E_USER_DEPRECATED);

//--Define configuration used by App singleton, such as paths.
define(__NAMESPACE__ . '\PROJECT_DIR', realpath(__DIR__ . '/..'));
define(__NAMESPACE__ . '\VENDOR_DIR', constant(__NAMESPACE__ . '\PROJECT_DIR') . '/vendor');
$loader = require(constant(__NAMESPACE__ . '\VENDOR_DIR') . '/autoload.php');
App::getSingleton([
	'loader'=> $loader
	,'paths'=> [
		//--cli paths
		'PHPCLI'=> '/usr/bin/php'
		//--symfony paths
		,'app'=> constant(__NAMESPACE__ . '\PROJECT_DIR') . '/app'
		,'project'=> constant(__NAMESPACE__ . '\PROJECT_DIR')
		,'src'=> constant(__NAMESPACE__ . '\PROJECT_DIR') . '/src'
		,'var'=> constant(__NAMESPACE__ . '\PROJECT_DIR') . '/var'
		,'vendor'=> constant(__NAMESPACE__ . '\VENDOR_DIR')
	]
]);
//-# uncomment to enable Symfony's HTTPCache in 'prod' environment
// App::getSingleton()->setCache('prod');

return App::getLoader();
