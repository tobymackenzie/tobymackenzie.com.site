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
<article class="post post-none post-0 mainItem" id="post-0">
	<header class="postHeader">
		<h1 class="postHeading"><?php _e('Nothing found', 'tmcom'); ?></h1>
	</header>
	<div class="postContent">
<?php if(is_search()){ ?>
		<p class="notice"><?php _e('Sorry, but your search did not match anything on this site. Please try a different search.', 'tmcom'); ?></p>
<?php }else{ ?>
		<p><?php _e('Sorry, but what you are looking for could not be found. Perhaps you can find what you are looking for with a search.', 'tmcom'); ?></p>
<?php } ?>
		<?php get_search_form(); ?>
	</div>
</article>
