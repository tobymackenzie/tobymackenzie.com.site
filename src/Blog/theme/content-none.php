<?php
/*=====
Content template: Renders the main content area when no posts or pages are found.
=====*/

//=====debug
if(WP_DEBUG){
?>
<!--Debug:
	@Template TJMBase/content-none.php
-->
<?php
}

//=====content
?>
<article class="post post-none post-0 m--0" id="post-0">
	<header class="postHeader">
<?php if(is_search()){ ?>
		<h1 class="postHeading"><?php _e('Nothing found', 'tmweb'); ?></h1>
<?php }else{ ?>
		<h1 class="postHeading"><?php _e('Error 404: Not Found', 'tmweb'); ?></h1>
<?php } ?>
	</header>
	<div class="postContent">
<?php if(is_search()){ ?>
		<p class="notice"><?php _e('Sorry, but your search did not match anything on this site. Please try a different search.', 'tmweb'); ?></p>
<?php }else{ ?>
		<p><?php _e('The page you requested was not found.  Try searching for it with the form below, or peruse the <a href="/">home page</a>.', 'tmweb'); ?></p>
<?php } ?>
		<br />
		<?php get_search_form(); ?>
	</div>
</article>
