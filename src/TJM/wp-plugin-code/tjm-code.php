<?php
/*
Plugin Name: tjm-code
Plugin URI:  https://www.tobymackenzie.com/blog/2016/04/06/wordpress-code-plugin/
Description: Simple handler of 'code' shorttag
Version: 0.1
Author: Toby Mackenzie
Author URI: https://www.tobymackenzie.com
License: licenseName
*/

add_shortcode('code', function($args = null, $content = null){
	$content = trim($content);
	// $content = trim($content, '<br />');
	$content = str_replace('<br />', '', $content);
	$content = str_replace('<p>', '', $content);
	$content = str_replace('</p>', '', $content);
	if(ord($content{0}) === 10){
		$content = substr($content, 1);
	}
	if($args && isset($args['lang'])){
		return '<pre><code class="' . $args['lang'] . '">' . $content . '</code></pre>';
	}else{
		return '<pre><code>' . $content . '</code></pre>';
	}
});
