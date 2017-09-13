<?php
add_action('wp_enqueue_scripts', function(){
	if(is_child_theme()){
		wp_enqueue_style('parent-stylesheet', get_template_directory_uri() . '/style.css', false);
	}
	wp_enqueue_style('theme-stylesheet', get_stylesheet_directory_uri() . '/style.css', false);
	//--force https if browser supports it
	if(!is_ssl()){
		//-# putting in post because I'm not sure if old browsers will fail loading content if they can't load the script
		wp_enqueue_script('forceHttps',   'https://' . $_SERVER['HTTP_HOST'] . '/bundles/public/scripts/prod/forceHttps.js', false, null, true);
	}
});
