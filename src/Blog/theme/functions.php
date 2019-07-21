<?php
use Symfony\Component\HttpFoundation\Request;
use TJM\Bundle\StandardEditionBundle\Component\App\App;
use TJM\WPThemeHelper\SettingHelper;
use TJM\WPThemeHelper\WPThemeHelper;

class SymfonyHelper{
	static protected $container;
	static protected $kernel;
	static public $viewData = [
		'doc'=> []
	];
	static public function getContainer(){
		if(!static::$container){
			static::$container = static::getKernel()->getContainer();
		}
		return static::$container;
	}
	static public function getKernel(){
		if(!static::$kernel){
			$env = (defined('WP_DEBUG') && WP_DEBUG ? 'dev' : 'prod');
			$app = require_once __DIR__ . '/../../PublicBundle/bootstrap.php';
			$app->setEnvironment($env);
			$kernel = $app->getKernel();
			$kernel->boot();
			$kernel->getContainer()->get('request_stack')->push(Request::createFromGlobals());
			static::$kernel = $kernel;
		}
		return static::$kernel;
	}
	static public function getService($name){
		return static::getContainer()->get($name);
	}
	static public function getViewData(){
		$request = static::getService('request_stack')->getCurrentRequest();
		if(!isset(static::$viewData['canonical'])){
			static::$viewData['canonical'] = 'https://www.tobymackenzie.com' . $request->server->get('REQUEST_URI');
		}
		return static::getService('TJM\Views\Views')->getDocData($request, static::$viewData);
	}
}
class TMWebWPTheme{
	static public $helper;
	static public function getPostTitle($post = null){
		global $wp_query;
		$title = null;
		if(!$post){
			$post = $wp_query->get_queried_object();
		}
		if(is_object($post)){
			if($post->post_name){
				$title = $post->post_name;
			}else{
				$post = $post->ID;
			}
		}
		if(!$title){
			$title = '#' . $post;
		}
		return $title;
	}
	static public function getPostType(){
		if(is_home()){
			$type = 'home';
		}elseif(is_page()){
			$type = 'page';
		}elseif(is_single()){
			$type = 'single';
		}else{
			$type = 'other';
		}
		return $type;
	}
}
TMWebWPTheme::$helper = new WPThemeHelper([
]);



add_action('wp_enqueue_scripts', function(){
	//--force https if browser supports it
	if(!is_ssl()){
		//-# putting in post because I'm not sure if old browsers will fail loading content if they can't load the script
		wp_enqueue_script('forceHttps',   'https://' . $_SERVER['HTTP_HOST'] . '/bundles/public/scripts/prod/forceHttps.js', false, null, true);
	}
});
add_filter('get_the_archive_title', function($title){
	if(is_category()){
		$title = single_cat_title('', false) . __(' posts', 'tjm');
	}elseif(is_date()){
		if(is_year()){
			$format = 'Y';
		}elseif(is_month()){
			$format = 'F Y';
		}elseif(is_day()){
			$format = 'F j, Y';
		}
		if(isset($format)){
			$title = get_the_date($format) . __(' posts', 'tjm');
		}
	}elseif(is_tag()){
		$title = single_tag_title('', false) . __(' posts', 'tjm');
	}elseif(is_author()){
		$title = __('Posts by ', 'tjm') . get_the_author();
	}
	return $title;
});
add_filter('wp_title', function($title){
	$title = get_the_title();
	if(!is_home()){
		$title .=  ' - Blog';
	}
	return $title;
});
add_filter('the_title', function($title, $id){
	//-# admin post page not seen as in loop
	if(is_single() || is_admin()){
		if(!$title || ($title === 'Previous Post' || $title === 'Next Post')){
			$title = TMWebWPTheme::getPostTitle($id);
		}
	}elseif(in_the_loop()){
		//--do nothing if in loop, because we want to not show title at all in loop if no title provided
	}else{
		if(is_home()){
			$title = get_bloginfo('name');
		}elseif(is_archive()){
			$title = get_the_archive_title();
		}elseif(is_search()){
			$title = 'Posts matching "' . htmlspecialchars(get_query_var('s')) . '"';
		}
		$paged = get_query_var('paged');
		if($paged && $paged != 1){
			$title .= ' page ' . $paged;
		}
	}
	return $title;
}, 10, 2);
