<?php
namespace TJM\Bundle\StandardEditionBundle\Component\App;

use Composer\Autoload\ClassLoader;

error_reporting(error_reporting() & ~E_USER_DEPRECATED);

$app = require_once(__DIR__ . '/config/app.php');

/**
* @var ClassLoader $app['loader']
*/
$app['loader'] = require(constant(__NAMESPACE__ . '\VENDOR_DIR') . '/autoload.php');

/*
Define configuration used by App singleton, such as paths.
*/
App::getSingleton($app);
//-# uncomment to enable Symfony's HTTPCache in 'prod' environment
// App::getSingleton()->setCache('prod');

return $app['loader'];
