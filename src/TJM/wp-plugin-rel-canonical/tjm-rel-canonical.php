<?php
/*
Plugin Name: tjm-rel-canonical
Plugin URI: https://www.tobymackenzie.com/blog/2016/04/13/wordpress-rel-canonical-plugin/
Description: modify the rel canonical links to have https instead of http
Version: 0.1
Author: Toby Mackenzie
Author URI: https://www.tobymackenzie.com
License: GPL2
*/

remove_action('wp_head', 'rel_canonical');
add_action('wp_head', function(){
	if(!is_singular()){
		return;
	}
	$id = get_queried_object_id();
	if(!$id){
		return;
	}
	$cpage = get_query_var('cpage');
	if($cpage){
		$url = get_comments_pagenum_link($cpage);
	}else{
		$url = get_permalink($id);
		$page = get_query_var('page');
		if($page > 1){
			if(get_option('permalink_structure') == ''){
				$url = add_query_arg('page', $page, $url);
			}else{
				$url = trailingslashit($url) . user_trailingslashit($page, 'single_paged');
			}
		}
	}
	$url = str_replace('http://', 'https://', $url);
	echo "<link href=\"{$url}\" rel=\"canonical\" />\n";
});
