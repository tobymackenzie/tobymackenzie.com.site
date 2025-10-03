<?php
use PublicApp\Listener\ViewDataListener;
use Symfony\Component\HttpFoundation\Request;
use TJM\SyWeb\App;
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
			$app = require_once __DIR__ . '/../../PublicApp/bootstrap.php';
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
		return static::getService(ViewDataListener::class)->getDocData($request, static::$viewData);
	}
}
class TMWebWPTheme{
	static public $helper;
	static public function doesPostHaveMore($post = null){
		if(has_excerpt($post)){
			return true;
		}
		if(!is_object($post)){
			$post = get_post($post);
		}
		//-@ https://wordpress.stackexchange.com/a/59257
		return is_object($post) && strpos($post->post_content, '<!--more-->') !== false;
	}
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

//--remove emoji script / styles
//-@ based on https://www.wpfaster.org/code/how-to-remove-emoji-styles-scripts-wordpress
add_action('init', function(){
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('admin_print_styles', 'print_emoji_styles');
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');

	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

	add_filter('tiny_mce_plugins', function($plugins){
		if(is_array($plugins)){
			return array_diff($plugins, ['wpemoji']);
		}else{
			return [];
		}
	});
	add_filter('wp_resource_hints', function($urls, $relationType){
		if($relationType === 'dns-prefetch'){
			$urls = array_filter($urls, function($url){
				return !preg_match('!^http[s]?://s.w.org/images/core/emoji!', $url);
			});
		}
		return $urls;
	}, 10, 2);
});

//--better looking ellipse for auto-generated excerpt
add_filter('excerpt_more', function(){
	return '…';
});

//--title output
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

//---remove feed links
add_action('after_setup_theme', function(){
	//---comment feed links
	add_theme_support('automatic-feed-links');
	add_filter('feed_links_show_comments_feed', '__return_false');
	//--rest api links
	remove_action('wp_head', 'rest_output_link_wp_head', 10);
	//--oembed
	remove_action('wp_head', 'wp_oembed_add_discovery_links');
});

//--prevent indexing some pages not useful in search results
add_filter('wp_robots', function($robots){
	if(is_tag()){
		unset($robots['max-image-preview']);
		$robots['noindex'] = true;
		$robots['follow'] = true;
	}
	return $robots;
});
