<?php
/*
Plugin Name: tjm-rel-canonical
Plugin URI: https://www.tobymackenzie.com/blog/2016/04/13/wordpress-rel-canonical-plugin/
Description: modify the rel canonical links to have https instead of http
Version: 0.1
Author: Toby Mackenzie
Author URI: https://www.tobymackenzie.com
License: 0BSD OR GPL-2.0-or-later
*/

//--use our template canonical instead of WPs
remove_action('wp_head', 'rel_canonical');
//--ensure WPs canonical is always HTTPS
add_filter('get_canonical_url', function($url){
	$url = str_replace('http://', 'https://', $url);
	return $url;
});
