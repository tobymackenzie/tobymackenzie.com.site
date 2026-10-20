<?php
if(!defined('ENV')){
	define('ENV', 'prod');
}
define('GH_PAGES', getenv('GITHUB_ACTIONS') && getenv('GITHUB_WORKSPACE'));
define('PROJECT_PATH', realpath(__DIR__ . '/../..'));
define('AUTOLOAD_PATH', (GH_PAGES ? getenv('GITHUB_WORKSPACE') : PROJECT_PATH) . '/vendor/autoload.php');

define('CARD_PATH', realpath(__DIR__));
define('DATA_PATH', CARD_PATH . '/../../data');
define('INDEX_PATH', CARD_PATH . '/index.php');
define('JS_PATH', CARD_PATH . '/scripts.js');
define('STYLES_PATH', CARD_PATH . '/styles.css');

define('DIST_PATH', GH_PAGES ? getenv('GITHUB_WORKSPACE') . '/dist' : PROJECT_PATH . '/dist/card');
define('INDEX_DIST_PATH', DIST_PATH . '/index.html');
define('JS_DIST_PATH', DIST_PATH . '/_scripts.js');
define('STYLES_DIST_PATH', DIST_PATH . '/_styles.css');
