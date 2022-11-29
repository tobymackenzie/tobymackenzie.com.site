<?php
if(!defined('ENV')){
	define('ENV', 'prod');
}
define('PROJECT_PATH', realpath(__DIR__ . '/../..'));
define('AUTOLOAD_PATH', PROJECT_PATH . '/vendor/autoload.php');

define('SHORT_PATH', realpath(__DIR__));
define('INDEX_PATH', SHORT_PATH . '/index.php');
define('STYLES_PATH', SHORT_PATH . '/styles.css');

define('DIST_PATH', PROJECT_PATH . '/dist/short');
define('INDEX_DIST_PATH', DIST_PATH . '/index.html');
define('JS_DIST_PATH', DIST_PATH . '/../public/_assets/scripts/short.js');
