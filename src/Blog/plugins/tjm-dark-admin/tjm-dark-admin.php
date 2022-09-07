<?php
/*
Plugin Name: tjm-dark-admin
Description: quick and dirty dark(ish) mode for wp admin
Version: 0.1
Author: Toby Mackenzie
Author URI: https://www.tobymackenzie.com
License: GPL2
*/

add_action('admin_enqueue_scripts', function(){
	wp_enqueue_style('tjm-dark-admin', plugin_dir_url(__FILE__) . '/styles.css');
});
