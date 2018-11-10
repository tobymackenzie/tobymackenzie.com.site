<?php
/*=====
Content template: Renders the main content area when no more specific content templates exist.  TJMBase uses this template for all content types (eg 'index', 'single', 'archive', 'page', and 'search') except 'none'.
=====*/


//=====debug
if(WP_DEBUG){
?>
<!--Debug:
	@Template tmcom/content.php
-->
<?php
}

//=====content
$thumbnail = (is_single() ? get_the_post_thumbnail() : null);
$title = get_the_title();
$permalinkTitle = ($title ?: TMComWPTheme::getPostTitle(get_the_ID()));

$postType = TMComWPTheme::getPostType();
?>
<article <?php post_class("post post-default post-" . get_the_ID() . " h-entry mainItem"); ?> id="post-<?php the_ID(); ?>">
<?php
if($title || $thumbnail){
?>
	<header class="postHeader">
<?php
	if($thumbnail){
?>
		<div class="postHeaderMedia"><?=$thumbnail?></div>
<?php
	}
	if($title){
		if(is_page() || is_single()){
?>
		<h1 class="postHeading p-name"><?=$title?></h1>
<?php
		}else{
?>
		<h2 class="postHeading p-name"><a class="postHeadingAction u-url" href="<?php the_permalink(); ?>"><?=$title?></a></h2>
<?php
		}
	}
?>
	</header>
<?php
}
?>
	<header class="postMeta">
<?php
if(!is_page()){
?>
		<?php if(!$title){ ?><a class="permalink u-url" href="<?=get_the_permalink()?>" title="<?=esc_attr(sprintf(__('Post "%s"', 'tmcom'), $permalinkTitle))?>" rel="bookmark"><?php } ?>
			<time class="dt-published postTime" datetime="<?php the_time('Y-m-d G:i') ?>" pubdate="pubdate"><?php the_time('F jS, Y \a\t G:i') ?></time>
		<?php if(!$title){ ?></a><?php } ?>
<?php
	if(!is_category()){
		echo TMComWPTheme::$helper->renderer->renderPiece('categories');
	}
	if(is_single()){
		echo TMComWPTheme::$helper->renderer->renderPiece('tags', Array('tags'=> get_the_tags()));
	}
}
if(is_single()){
?>
		<?php edit_post_link(__('Edit post', 'tmcom'), '<span class="editActionWrap">', '</span>'); ?>
<?php
}
if(is_single()){ ?>
		<div><a class="postPermalink permalink u-url" href="<?=get_the_permalink()?>" title="<?=esc_attr(sprintf(__('Permalink to %s', 'tmcom'), $permalinkTitle))?>" rel="bookmark"><?php _e('Permalink', 'tmcom'); ?></a></div>
<?php
}
//-! old theme had post type as link (`/type/{type}/`)
?>
	</header>
<?php

//--Output post content.  Excerpt for search results, full content for everything else.
if(is_search()){
?>
	<div class="postContent p-summary"><?php the_excerpt(); ?></div>
<?php }else{ ?>
	<div class="postContent e-content">
		<?php the_content(__('Continue reading', 'tmcom') . '<span class="sro"> post "' . $permalinkTitle . '"</span>'); ?>
		<?php wp_link_pages(Array(
			'after'=> '</div>'
			,'before'=> '<div class="pageLinks">' . __('Pages:', 'tmcom')
		)); ?>
	</div>
<?php } ?>
</article>
<?php
if($postType === 'single' || $postType === 'page'){
	comments_template('', true);
}
