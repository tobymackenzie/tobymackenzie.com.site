<?php
namespace TJM\Blog;
use TJM\Component\Utils\Arrays;
use TJM\WPThemeHelper\ConfigHelper;

define(__NAMESPACE__ . '\\PROJECT_PATH', dirname(realpath(__FILE__)) . '/../..');
require_once(PROJECT_PATH . '/vendor/autoload.php');

ConfigHelper::init((function(){
	$config = [
		'constants'=> [
			//--cookies
			'ADMIN_COOKIE_PATH'=> '/',

			//--paths
			'ABSPATH'=> dirname(realpath(__FILE__)) . '/../../../vendor/wp/wordpress/',
			'WP_CONTENT_DIR'=> "{{ConfigHelper::vars.webRootPath}}/{{ConfigHelper::vars.contentRelativePath}}",

			//--urls
			'WP_HOME'=> '{{ConfigHelper::vars.webRootUrl}}/blog',
			'WP_CONTENT_URL'=> "{{ConfigHelper::vars.webRootUrl}}/{{ConfigHelper::vars.contentRelativePath}}",
			'WP_SITEURL'=> "{{ConfigHelper::vars.webRootUrl}}/{{ConfigHelper::vars.wpRelativePath}}",

			//--security
			'AUTH_KEY'=> '!!12345',
			'DISALLOW_FILE_EDIT'=> true,
			'SECURE_AUTH_KEY'=> '!!12345',
			'LOGGED_IN_KEY'=> '!!12345',
			'NONCE_KEY'=> '!!12345',
			'AUTH_SALT'=> '!!12345',
			'SECURE_AUTH_SALT'=> '!!12345',
			'LOGGED_IN_SALT'=> '!!12345',
			'NONCE_SALT'=> '!!12345',
		],
		'globals'=> [
			'table_prefix'=> '15wp_',
		],
		'vars'=> [
			'contentRelativePath'=> '_/wp-content',
			// 'debug'=> true,
			'webRootPath'=> PROJECT_PATH . '/dist/public',
			'webRootUrl'=> '{{ConfigHelper::vars.protocol}}://{{ConfigHelper::vars.host}}',
			'wpRelativePath'=> '_/wp',
		],
	];
	$local = PROJECT_PATH . '/config/blog.local.php';
	if(file_exists($local)){
		$config = Arrays::deepMerge($config, include($local));
	}
	return $config;
})());
if(!isset($table_prefix) && isset($GLOBALS['table_prefix'])){
	$table_prefix = $GLOBALS['table_prefix'];
}
require_once(ABSPATH . 'wp-settings.php');
